<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');
/**
 * CLASE MASTER
 * Esta clase se encarga de controlar el dinamismo del foro, ya sean
 * configuraciones, Sesiones de usuario, control anti-flood, demonios, etc.
 */
class Master
 {
  /**
   * ID del usuario a trabajar
   */
  public static $id = null;

  /**
   * Instancia de LittleDB.
   */
  protected $db = null;

  /**
   * Arreglo con las acciones posibles
   */
  protected static $access = array();

  /**
   * Datos públicos del usuario
   */
  public static $data = array();



  /**
   * Configuracion del sitio
   */
  public static $config = array();


  /**
   * Constructor de la clase
   * @param object $db Instancia de LittleDB
   * @author Cody Roodaka <roodakazo@hotmail.com
   */
  public function __construct($db)
   {
    $this->db = $db;
    $this->set_config();

    if(session_id() == '' || !isset($_SESSION))
     {
      // Iniciamos datos predeterminados para la sesión
      session_start();
      $_SESSION['ip'] = ip2long(getip());
      $_SESSION['nav'] = md5($_SERVER['HTTP_USER_AGENT']);
      $_SESSION['time'] = time();
     }

    // Cargamos la sesión
    $this->load_session();

    if(self::$id == null)
     {
      // No hay usuario, cargamos las variables básicas necesarias
      self::$data['rango_id'] =  self::$config['user_visitor_default_grade'];
      self::$data['config_tema'] = self::$config['site_defaulttheme'];
      self::$data['config_idioma'] = self::$config['site_defaultlang'];
      self::$data['mensajes'] = '0';
     }
    else
     {
      // Seteamos los datos de usuario
      $this->set_data();
     }

    // Configuramos Rain para trabajar
    raintpl::configure('base_url', self::$config['site_patch']);
    raintpl::configure('tpl_dir', 'themes/'.self::$data['config_tema'].'/');
    raintpl::configure('cache_dir', LFS_ROOT.'cache/'.self::$data['config_tema'].'/');
   } // public function __construct();



  /**
   * Seteamos los datos
   * @author Cody Roodaka <roodakazo@hotmail.com
   */
  private function set_data()
   {
    self::$data = $this->db->query('SELECT COUNT(DISTINCT(m.id)) AS unreads, u.id, u.rango_id, u.mensajes, u.config_idioma, u.config_tema, u.nombre, u.avatar, u.sexo FROM '.$this->db->prefix.'usuarios AS u LEFT JOIN '.$this->db->prefix.'mensajes AS m ON m.user_id = u.id || user_id = u.id WHERE u.id = ? LIMIT 1', array(self::$id), true);
   } // public function set_data();



  /**
   * Cargamos las configuraciones del servidor
   * @author Cody Roodaka <roodakazo@hotmail.com
   */

  private function set_config()
   {
    $result = $this->db->query('SELECT * FROM '.$this->db->prefix.'config', null, false);
    while($row = $result->fetchrow())
     {
      self::$config[$row['clave']] = $row['valor'];
     }
   } // public function set_config();



  /**
   * Cargamos los datos del paquete de idiomas instalado
   * @return array Arreglo con los datos.
   * @author Cody Roodaka <roodakazo@hotmail.com
   */
  public function get_languaje_data()
   {
    $result = array();
    // Cargamos los datos del archivo de idioma
    $result['data'] = $this->db->query('SELECT * FROM '.$this->db->prefix.'lenguajes WHERE id = ?', self::$data['config_idioma'], true);
    if(is_file('lang/'.$result['data']['archivo'].'.php'))
     {
      // Cargamos y seteamos las variables en el archivo de idioma.
      $result['vars'] = require('lang/'.$result['data']['archivo'].'.php');
     }
    else { exit('ERROR: No existe el archivo de Idiomas.'); }
    return $result;
   } // public function get_languaje();



  /**
   * Cargamos los datos de sesión
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com
   */
  private function load_session()
   {
    // Hay una cookie?
    if(isset($_COOKIE[self::$config['cookie_name']]) && self::$config['enable_cookies'] == true)
     {
      $hash = $_COOKIE[self::$config['cookie_name']];
      $table = 'cookies';
     }

    // Si existe la sesion, la cargamos
    elseif(isset($_SESSION['id']))
     {
      $hash = $_SESSION['id'];
      $table = 'sesiones';
     }

    // No hay nada valido, no cargamos
    else
     {
      $hash = false;
      $table = '';
     }

    // Chequeamos que haya una id de sesion
    if($hash !== false)
     {
      // cargamos los datos desde la db
      $data = $this->db->query('SELECT user_id, nav, ip FROM '.$this->db->prefix.$table.' WHERE hash = ? LIMIT 1', $hash, true);
      if(!$data || $data == false) { return false; }
      else
       {
        if($_SESSION['ip'] == $data['ip'])
         {
          if($_SESSION['nav'] == $data['nav'])
           {
            if($_SESSION['time'] > (time() - self::$config['user_session_life']))
             {
              self::$id = $data['user_id'];
              $_SESSION['time'] = time();
              $this->db->update('usuarios', array('ultimaip' => $data['ip'], 'ultimafecha' => $_SESSION['time']), array('id' => $data['user_id']),false);
              return true;
             }
           }
         }
        // Borramos todos los datos de la sesion
        $this->db->delete('sesiones', array('hash' => $hash), false);
        unset($_SESSION);
        if(isset($_COOKIE))
         {
          $this->db->delete('cookies', array('hash' => $hash), false);
          unset($_COOKIE);
         }
        session_destroy();
       }
     }
    self::$id = null;
    return false;
   } // public function load_session();
 } // class Master();