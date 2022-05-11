<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');

class foros
 {
  /**
   * Instancia de LittleDB
   */
  protected $db = null;

  const COND_GRADE = 0;  // Sólo a los usuarios de X rango
  const COND_GROUP = 1;  // Sólo a miembros de X grupo
  const COND_USER = 2;  // Sólo a ciertos usuarios
  const FORUM_COMMON = 0;  // Foro común
  const FORUM_REDIR = 1;  // Foro con vínculo Externo
  const FORUM_LOCKED = 2;  // Foro en el que no se puede crear o responder temas

  /**
   * Constructor de la clase
   * @param object $db Instancia de LittleDB
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function __construct($db)
   {
    $this->db = $db;
   } // public function __construct();



  /**
   * Obtener el Arbol de foros para el menu de navegacion
   * @param boolean $home Indicar si se prepara para la home o la lista en la barra lateral
   * @return array Lista de foros
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function get_forums_tree($home = false)
   {
    if($home === false) { $cachename = 'forums_tree_leftbar'; }
    else { $cachename = 'forums_tree_home'; }
    $cached = Cache::get($cachename);
    if($cached !== false) { return $cached; }
    else
     {
      $cats = $this->db->query('SELECT id, nombre, nivel FROM '.$this->db->prefix.'categorias ORDER BY orden ASC LIMIT 0, 30', null, false);
      $c = 0;
      $result = array();
      while($cat = $cats->fetchrow())
       {
        $c = ++$c;
        $result[$c] = $cat;
        // Obtenemos los foros.
        if($home === true) { $foros = $this->db->query('SELECT f.id, f.nombre, f.descripcion, f.tipo, f.redireccion, f.hits, f.nivel_ver, f.temas, f.respuestas, v.fechahora AS visto, t.fechahora AS tema_fechahora, t.id AS tema_id, t.titulo, tr.fechahora AS resp_fechahora, u.id AS user_id, u.nick, r.nombre AS rango, r.color, r.bold FROM '.$this->db->prefix.'foros AS f LEFT JOIN '.$this->db->prefix.'foros_leidos AS v ON v.foro_id = f.id '.((Master::$id !== null) ? '&& v.user_id = '.Master::$id : '').' LEFT JOIN '.$this->db->prefix.'temas AS t ON t.foro_id = f.id && t.estado = 1 LEFT JOIN '.$this->db->prefix.'temas_respuestas AS tr ON tr.tema_id = t.id && tr.estado = 1 LEFT JOIN '.$this->db->prefix.'usuarios AS u ON u.id = tr.user_id || u.id = t.user_id LEFT JOIN '.$this->db->prefix.'rangos AS r ON r.id = u.rango_id WHERE f.cat_id = ? && f.padre_id = 0 GROUP BY f.id ORDER BY f.nombre ASC, tr.fechahora DESC LIMIT 0, 30', $cat['id'], false); }
        else { $foros = $this->db->query('SELECT id, nombre, descripcion, nivel_ver, temas, respuestas FROM '.$this->db->prefix.'foros WHERE cat_id = ? && padre_id = 0 ORDER BY nombre LIMIT 0, 30', $cat['id'], false); }
        if($foros != false)
         {
          $forums = array();
          $f = 0;
          while($foro = $foros->fetchrow())
           {
            $f = ++$f;
            $forums[$f] = $foro;
            if($home == true)
             {
              $forums[$f]['mods'] = $this->get_moderators($foro['id']);
             }
            // Ahora por los subforos xD
            $subs = $this->db->query('SELECT id, nombre, descripcion FROM '.$this->db->prefix.'foros WHERE padre_id = ?', $foro['id'], false);
            if($subs != false)
             {
              $subforos = array();
              while($sub = $subs->fetchrow()) { $subforos[] = $sub; }
              $forums[$f]['subs'] = $subforos;
             }
           }
          $result[$c]['foros'] = $forums;
         }
       }
      Cache::set($cachename, $result, (60*10));
      return $result;
     }
   } // public function get_forums_tree();



  /**
   * Obtenemos los foros o subforos de una determinada categoria o padre
   * @param int $father ID del foro padre.
   * @return array Lista de subforos
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function get_subforums($father)
   {
    $cached = Cache::get('subforos_'.$father);
    if($cached !== false)
     {
      return $cached;
     }
    else
     {
      $foros = $this->db->query('SELECT f.id, f.nombre, f.descripcion, f.tipo, f.redireccion, f.hits, f.nivel_ver, f.temas, f.respuestas, v.fechahora AS visto, t.fechahora AS tema_fechahora, t.id AS tema_id, t.titulo, tr.fechahora AS resp_fechahora, u.id AS user_id, u.nick, r.nombre AS rango, r.color, r.bold FROM '.$this->db->prefix.'foros AS f LEFT JOIN '.$this->db->prefix.'foros_leidos AS v ON v.foro_id = f.id '.((Master::$id !== null) ? '&& v.user_id = '.Master::$id : '').' LEFT JOIN '.$this->db->prefix.'temas AS t ON t.foro_id = f.id && t.estado = 1 LEFT JOIN '.$this->db->prefix.'temas_respuestas AS tr ON tr.tema_id = t.id && tr.estado = 1 LEFT JOIN '.$this->db->prefix.'usuarios AS u ON u.id = tr.user_id || u.id = t.user_id LEFT JOIN '.$this->db->prefix.'rangos AS r ON r.id = u.rango_id WHERE f.padre_id = ? GROUP BY f.id ORDER BY f.nombre ASC, tr.fechahora DESC LIMIT 0, 10', $father, false);
      if($foros->numrows() >= 1)
       {
        $f = 1;
        $result = array();
        while($foro = $foros->fetchrow())
         {
          $f = ++$f;
          $result[$f] = $foro;
          $result[$f]['conditions'] = $this->get_conditions($foro['id']);
          $result[$f]['mods'] = $this->get_moderators($foro['id']);
         }
        Cache::set('subforos_'.$father, $result, 0);
        return $result;
       }
      else { return false; }
     }
   } // public function get_subforums();



  /**
   * Obtener los datos de un foro
   * @param int $id id del foro objetivo
   * @return array Datos del foro
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function get_forum_data($id, $mods = true)
   {
    $cached = Cache::get('forum_'.$id.'_data');
    if($cached !== false) { return $cached; }
    else
     {
      $query = $this->db->query('SELECT f.id, f.nombre, f.descripcion, f.tipo, f.redireccion, f.hits, f.nivel_ver, f.temas, f.respuestas, v.fechahora AS visto, t.fechahora AS tema_fechahora, t.id AS tema_id, t.titulo, tr.fechahora AS resp_fechahora, u.id AS user_id, u.nick, r.nombre AS rango, r.color, r.bold
FROM '.$this->db->prefix.'foros AS f
LEFT JOIN '.$this->db->prefix.'foros_leidos AS v ON v.foro_id = f.id '.((Master::$id !== null) ? '&& v.user_id = '.Master::$id : '').'
LEFT JOIN '.$this->db->prefix.'temas AS t ON t.foro_id = f.id && t.estado = 1
LEFT JOIN '.$this->db->prefix.'temas_respuestas AS tr ON tr.tema_id = t.id && tr.estado = 1
LEFT JOIN '.$this->db->prefix.'usuarios AS u ON u.id = tr.user_id || u.id = t.user_id
LEFT JOIN '.$this->db->prefix.'rangos AS r ON r.id = u.rango_id
WHERE f.id = ? GROUP BY f.id ORDER BY f.nombre ASC, tr.fechahora DESC LIMIT 0, 1', $id, true);
      if(!$query || $query === false) { return false; }
      else
       {
        $query['condiciones'] = $this->get_conditions($query['id']);
        Cache::set('forum_'.$id.'_data', $query, 0);
        return $query;
       }
     }
   } // public function get_forum_data();



  public function get_conditions($id)
   {
    $query = $this->db->query('SELECT tipo, objeto_id FROM '.$this->db->prefix.'foros_condicion WHERE foro_id = ?', (int) $id, false);
    if(!$query || $query === false) { return false; }
    else
     {
      $cond = array();
      $cond['usuarios'] = array();
      $cond['rangos'] = array();
      $cond['mods'] = $this->get_moderators($id);
      // $cond['grupos'] = array();
      while($row = $query->fetchrow())
       {
        if($row['tipo'] === self::COND_USER) { $cond['usuarios'][] = (int) $row['objeto_id']; }
        elseif($row['tipo'] === self::COND_GRADE) { $cond['rangos'][] = (int) $row['objeto_id']; }
        //elseif($row['tipo'] === self::COND_GROUP) { $cond['grupos'][] = (int) $row['objeto_id']; }
       }
      return $cond;
     }
   } // public function get_conditions();



  /**
   * Obtener lista de moderadores
   * @param int $fid id del foro objetivo
   * @return array Lista de moderadores
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function get_moderators($fid)
   {
    $cached = Cache::get('forum_'.$fid.'_moderators');
    if($cached !== false)
     {
      return $cached;
     }
    else
     {
      $query = $this->db->query('SELECT u.id, u.nick, u.nombre, r.nombre AS rango, r.color, r.bold FROM '.$this->db->prefix.'foros_moderadores AS l LEFT JOIN '.$this->db->prefix.'usuarios AS u ON u.id = l.user_id LEFT JOIN '.$this->db->prefix.'rangos AS r ON r.id = u.rango_id WHERE foro_id = ? GROUP BY u.id', $fid, false);
      if(!$query || $query == false) { return false; }
      else
       {
        $mods = array();
        while($mod = $query->fetchrow(false))
         {
          $mods['list'][] = $mod;
         }
        $mods['cant'] = $query->numrows();
        Cache::set('forum_'.$fid.'_moderators', $mods, (60*60));
        return $mods;
       }
     }
   } // public function get_moderators();



  /**
   * Asignar un nuevo moderador
   * @param int $uid ID del usuario a ser moderador local
   * @param int $fid id del foro objetivo
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function assign_moderator($uid, $fid)
   {
    return $this->db->insert('foros_moderadores', array(
     'foro_id' => $fid,
     'user_id' => $uid,
     'autoriza' => Master::$id,
     'fechahora' => time()), false);
   }



  /**
   * Crear un nuevo foro.
   * @param string $name Nombre
   * @param string $description Descripción
   * @param int $category ID de la Categoría
   * @param int $father ID del foro Padre
   * @param int $lv_see Nivel necesario para ver el foro
   * @param int $lv_create Nivel necesario para crear temas
   * @param int $lv_edit Nivel necesario para editar temas
   * @param int $lv_aprrobe Nivel necesario para aprobar temas
   * @param int $lv_stick Nivel necesario para fijar temas
   * @param int $lv_block Nivel necesario para bloquear temas
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
   public function new_forum($name, $description, $category, $father, $type, $redir_url, $redir_visits, $lv_see, $lv_create)
    {
     return $this->db->insert('foros', array(
      'cat_id' => (int) $category,
      'padre_id' => (int) $father,
      'nombre' => htmlspecialchars($name),
      'descripcion' => htmlspecialchars($description),
      'tipo' => (int) $type ,
      'redireccion' => $redir_url,
      'hits' => (int) $redir_visits,
      'nivel_ver' => (int) $lv_see,
      'nivel_crear' => (int) $lv_create,
      'temas' => 0,
      'respuestas' => 0,
      'ultimo_tema' => null
      ), false);
    } // public function new_forum();



  /**
   * Crear un nuevo foro.
   * @param int $id ID del foro objetivo
   * @param string $name Nombre
   * @param string $description Descripción
   * @param int $category ID de la Categoría
   * @param int $father ID del foro Padre
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function update_forum_data($id, $name, $description, $category, $father, $type, $redir, $hits, $level_see, $level_create)
   {
    return $this->db->update('foros',array(
      'cat_id' => (int) $category,
      'padre_id' => (int) $father,
      'nombre' => htmlspecialchars($name),
      'descripcion' => htmlspecialchars($description),
      'tipo' => (int) $type,
      'redireccion' => htmlspecialchars($redir),
      'hits' => (int) $hits,
      'nivel_ver' => (int) $level_see,
      'nivel_crear' => (int) $level_create
      ), array('id' => (int) $id), false);
   } // public function update_forum_data();



  /**
   * Borrar un foro
   * @param int $id ID del foro objetivo
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function delete_forum($id)
   {
    return $this->db->delete('foros', array('id' => (int) $id), false);
   } // public function delete_forum();



  /**
   * Marcar un foro como leído
   * @param $forum_id ID del foro objetivo
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function mark_forum_read($forum_id)
   {
    $check = $this->db->query('SELECT id FROM foros_leidos WHERE foro_id = ? && user_id = ? LIMIT 1', array((int) $forum_id, (int)Master::$id), true);
    if(!$check || $check === false)
     {
      return $this->db->insert('foros_leidos', array(
      'foro_id' => (int) $forum_id,
      'user_id' => (int) Master::$id,
      'fechahora' => time()), false);
     }
    else
     {
      return $this->db->update('foros_leidos', array('fechahora' => time()), array(
       'foro_id' => (int) $forum_id,
       'user_id' => (int) Master::$id), false);
     }
   } // public function mark_forum_read();



  public function is_marked($forum_id)
   {
    $check = $this->db->query('SELECT fechahora FROM foros_leidos WHERE foro_id = ? && user_id = ? LIMIT 1', array((int) $forum_id, (int)Master::$id), true);
    if(!$check || $check === false) { return false; }
    else { return (int) $check['fechahora']; }
   } // public function is_marked();



  /**
   * Chequeamos si puede entrar al foro
   * @param array $cond Arreglo con las condiciones del foro
   * @param int $id ID del foro
   * @return boolean Si puede entrar o no
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function can_enter($cond = null, $id = null)
   {
    if($cond === null && $id !== null) { $cond = $this->get_conditions((int) $id); }
    if(in_array(Master::$id, $cond['usuarios']) === true || in_array(Master::$data['rango_id'], $cond['rangos']) === true /** || in_array(Master::$data['grupo_id'], $cond['grupos']) === true */ || Grades::is_level(Grades::GRADE_ADMIN))
     {
      return true;
     }
    else
     {
      return false;
     }
   } // public function can_enter();



 } // class foros();