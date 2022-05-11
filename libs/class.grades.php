<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');
/**
 * Clase para gestionar los Grades (niveles de acceso|gerarquías)
 */
class Grades
 {
  // Gerarquías
  const GRADE_BANNED = 0;  // Usuario Baneado
  const GRADE_VISITOR = 1;  // Visitante no registrado
  const GRADE_COMMON = 2;  // Usuario común
  const GRADE_VIP = 3;  // Vip
  //const GRADE_ = 4;  // ??
  //const GRADE_ = 5;  // ??
  const GRADE_LMOD = 6;  // Moderador Local
  const GRADE_GMOD = 7;  // Moderador Global
  const GRADE_COLAB = 8;  // Colaborador (sub-admin)
  const GRADE_ADMIN = 9;  // Administrador

  // Usuarios (11)
  const USER_SEE_HOME = 1;  // Ver la Portada
  const USER_LOGIN = 2;  // Puede Logguearse
  const USER_NO_NEED_APROBATION = 3;  // Si tiene este permiso, los posts creados por este usuario no necesitan aprobacion
  const USER_EDIT_SIGNATURE = 4;  // Crear y Usar su firma
  const USER_EDIT_AVATAR = 5;  // Subir y mostrar un Avatar
  const USER_EDIT_USERS = 6;  // [Mod] Editar Usuarios
  const USER_BAN = 7;  // [Mod] Banear Usuarios
  const USER_SEE_IP = 8;  // [Mod] Ver la IP de otros usuarios
  const USER_DELETE_USERS = 9;  // [Mod] Borrar Usuarios
  const USER_HIDE_ON_LIST = 10;  // Ocultarlo en la lista de usuarios online
  const USER_VOTE = 11;  // Poder Votar otros usuarios
  const USER_SEE_ONLINELIST  = 12;  // Ver la lista de usuarios conectados

  // Aplican para Temas y Respuestas (9)
  const CONTENT_CREATE = 30;  // Publicar nuevo contenido
  const CONTENT_OWN_EDIT = 31;  // Editar contenido propio
  const CONTENT_OWN_DELETE = 32;  // Borrar contenido propio
  const CONTENT_SEE_HIDDEN = 33;  // [Mod] Ver contenido oculto
  const CONTENT_VOTE = 34;  // Votar contenido
  const CONTENT_APROBE = 35;  // [Mod] Aprobar los contenido de los usuarios novatos
  const CONTENT_ALL_EDIT = 36;  // [Mod] Editar contenido general
  const CONTENT_ALL_HIDE = 37;  // [Mod] Ocultar contenido
  const CONTENT_ALL_DELETE = 38;  // [Mod] Borrar contenido

  // Moderación (2)
  const MODERATION_JUMP_LIMITS = 50;  // Saltarse los límites de permiso (condicionantes de foros o de temas)
  const MODERATION_MANAGE_ADVERTENCES = 51;  // Ver y completar las advertencias

  // Administración (4)
  const SERVER_MANAGE = 70;  // Ver y utilizar el panel de administración
  const SERVER_MASTER =71 ;  // Ver y utilizar el panel Master
  const SERVER_MAINTENANCE = 72;  // Poner la web en modo mantenimiento
  const SERVER_SEE_LOGS = 73;  // Ver los registros
  const SERVER_SEE_STATS = 74;  // Ver el rendimiento del sistema

  // Tipos de Requerimientos
  const TYPE_AUTO = 0;
  const TYPE_ASSIGN = 1;


  protected static $grade_id = null;
  protected $db = null;
  protected static $data = array();
  protected static $second_data = array();

  public function __construct($db, $grade_id)
   {
    $this->db = $db;
    self::$data = $this->get_grade_data($grade_id);
   } // public function __construct();



  public function create($cond_type, $cond_value, $name, $color, $bold, $icon)
   {
    return $this->db->insert('rangos', array(
     'nivel_acceso' => '',
     'tipo' => (int) $cond_type,
     'cantidad' => (int) $cond_value,
     'nombre' => htmlspecialchars($name),
     'color' => $color,
     'bold' => (int) $bold,
     'icono' => htmlspecialchars($icon),
     'usuarios' => 0), false);
   } // public function create();



  public function get_grades($type = self::TYPE_ASSIGN)
   {
    if($type === self::TYPE_ASSIGN) { $order = 'nivel_acceso DESC, id DESC'; }
    elseif($type === self::TYPE_AUTO) { $order = 'cantidad DESC, id DESC'; }
    $query = $this->db->query('SELECT * FROM '.$this->db->prefix.'rangos WHERE tipo = ? ORDER BY '.$order.' LIMIT 0, 50', $type, false);
    if($query->query === false) { return false; }
    else
     {
      $grades = array();
      while($row = $query->fetchrow())
       {
        $grades[] = $row;
       }
      return $grades;
     }
   } // public function get_grades();



  public function add_access($grade_id, $access)
   {
    return $this->db->insert('rangos_acciones', array('nivel_acceso' => (int) $grade_id, 'accion' => (int) $access));
   } // public function add_access();



  public function remove_access($grade_id, $access)
   {
    return $this->db->delete('rangos_acciones', array('nivel_acceso' => $grade_id, 'accion' => $access), false);
   } // public function remove_access();



  /**
   * Remover un Rango completo (incluyendo todos sus permisos)
   * @param int
   */
  public function remove_grade($id)
   {
    $this->db->delete('rangos_acciones', array('rango_id' => $id), false);
    return $this->db->delete('rangos', array('id' => $id), false);
   } // public function remove_grade();



  /**
   * Cargamos los datos del rango
   * @param int $id ID del rango
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com
   */
  public function get_grade_data($id)
   {
    // Cargamos los datos del rango
    $data = $this->db->query('SELECT id, nivel_acceso, tipo, cantidad, nombre, color, bold, icono FROM '.$this->db->prefix.'rangos WHERE id = ? LIMIT 1', $id, true);

    // Cargamos los permisos dependiendo del nivel de acceso que éste tenga.
    $query = $this->db->query('SELECT accion FROM '.$this->db->prefix.'rangos_acciones WHERE nivel_acceso = ? ', $data['nivel_acceso'], false);
    if($query !== false)
     {
      $data['permisos'] = array();
      while($row = $query->fetchrow())
       {
        $data['permisos'][] = $row['accion'];
       }
      return $data;
     }
    else { $data['permisos'] = array('none'); }
    return $data;
   } // protected function set_access_data();



  /**
   * Chequeamos si el usuario tiene acceso a esa acción
   * @param int $action Acción a consultar
   * @param int $id ID del grado a consultar
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com
   */
  public static function is_access($action)
   {
    return in_array($action, self::$data['permisos']);
   } // static function is_access();



  /**
   * Chequeamos si el usuario tiene acceso a esa acción
   * @param string $action Acción a consultar
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com
   */
  public static function is_level($level)
   {
    return (self::$data['nivel_acceso'] >= (int) $level) ? true : false;
   } // public static function is_level();

 } // class Rangos();