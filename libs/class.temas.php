<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');

class Temas
 {
  /**
   * Instancia de LittleDB
   */
  protected $db = null;

  const TOPIC_STATUS_UNNAPPROBED = 0;
  const TOPIC_STATUS_APPROBED = 1;
  const TOPIC_STATUS_HIDDED = 2;
  const TOPIC_STATUS_DISAPPROBED = 3;
  const TOPIC_I_READED = 1;
  const TOPIC_I_PARTICIPE = 2;
  const TOPIC_I_UNREAD = 3;

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
   * Obtener datos de un tema
   * @param int $id ID del tema objetivo
   * @return object Instancia de Query con los datos de la consulta
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function get_topic($id)
   {
    $topic = $this->db->query('SELECT t.*, c.nombre AS p_nombre, u.id AS user_id, u.nick, u.nombre, u.avatar, u.rango_id, u.mensajes, u.firma_html, u.fecharegistro, l.nombre AS rango, l.icono AS rango_icono, l.color, l.bold FROM '.$this->db->prefix.'temas AS t LEFT JOIN '.$this->db->prefix.'temas_tipos AS c ON c.id = t.tipo LEFT JOIN '.$this->db->prefix.'temas_respuestas AS r ON r.tema_id = t.id LEFT JOIN '.$this->db->prefix.'usuarios AS u ON u.id = t.user_id LEFT JOIN '.$this->db->prefix.'rangos AS l ON l.id = u.rango_id WHERE t.id = ? LIMIT 1', array($id), true);
    if(!$topic || $topic === false || !is_array($topic)) { return false; }
    else
     {
      return $topic;
     }
   } // public function getTopic();




  /**
   * Obtenemos el ID del foro donde está publicado el tema
   * @param int $id ID del tema objetivo
   * @return int ID del foro
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function get_forum_of($id)
   {
    $topic = $this->db->query('SELECT foro_id FROM '.$this->db->prefix.'temas WHERE id = ? LIMIT 1', array($id), true);
    if(!$topic || $topic == false) { return false; }
    else
     {
      return $topic['foro_id'];
     }
   } // public function getTopic();



  public function get_source_tree($tid)
   {
    return $this->db->query('SELECT f.id AS foro_id, f.cat_id, f.nombre AS foro_nombre, c.nombre AS cat_nombre FROM '.$this->db->prefix.'temas AS t LEFT JOIN '.$this->db->prefix.'foros AS f ON f.id = t.foro_id LEFT JOIN '.$this->db->prefix.'categorias AS c ON c.id = f.cat_id WHERE t.id = ? GROUP BY t.id LIMIT 1' , $tid, true);
   } // public function get_source_tree(



  /**
   * Obtenemos los temas fijados, no se puede cambiar el orden, y la página
   * tampoco, ya que sólo se muestran en la primera
   * @param int $father Id del foro.
   * @return array Lista de temas
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function get_sicks($father)
   {
    $cached = Cache::get('forum_'.$father.'_sticks');
    if($cached !== false)
     {
      return $cached;
     }
    else
     {
      $query = $this->db->query('SELECT t.id AS tema_id, t.foro_id, t.titulo, t.fijado, t.user_id, t.prefijo, t.fechahora, t.ultima_respuesta, t.respuestas, t.visitas, c.nombre, c.color, c.icono, u.nick, r.nombre AS rango, f.nombre AS foro FROM '.$this->db->prefix.'temas AS t LEFT JOIN '.$this->db->prefix.'temas_tipos AS c ON c.id = t.tipo LEFT JOIN '.$this->db->prefix.'usuarios AS u ON u.id = t.user_id LEFT JOIN '.$this->db->prefix.'rangos AS r ON r.id = u.rango_id LEFT JOIN '.$this->db->prefix.'foros AS f ON t.foro_id = f.id WHERE t.foro_id = ? && t.fijado = 1 GROUP BY t.id ORDER BY t.fechahora DESC', $father, false);
      $f = 0;
      $array = array();
      // Armamos el arreglo final
      while($fijado = $query->fetchrow())
       {
        $array[$f] = $fijado;
        $f = ++$f;
       }
      $result = array('list' => $array, 'cant' => $query->numrows());
      Cache::set('forum_'.$father.'_sticks', $result, (60 * 15));
      return $result;
     }
   } // public function get_sticks();



  /**
   * Obtenemos los temas
   * @param int $father Id del foro.
   * @param string $order Orden en el que se muestran
   * @param int $page Nro de pagina
   * @param int $limit Limite por pagina
   * @param boolean $full Indica si cargar todos los temas o sólo los visibles.
   * @param int $uid ID del usuario a filtrar
   * @return array Lista de temas
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function get_topics($order = 't.fechahora DESC', $page = 1, $limit = 10, $father = null, $full = false, $uid = null, $key = null)
   {
    // Calculamos los limites basandonos en el numero de pagina
    $limits = paginate($page, $limit);

    // Si se indica un foro padre, se colocan los valores necesarios
    if($father !== null) { $where = 'WHERE t.foro_id = '.(int) $father.' && t.fijado = 0 '; }

    // Cargamos los temas sin discriminar importancia (?)
    else { $where = 'WHERE f.nivel_ver <= '.Master::$data['rango_id']; }

    // Definimos si cargar todos los temas o sólo los visibles
    if($full == false) { $where.= ' && t.estado = '.self::TOPIC_STATUS_APPROBED; }

    // Si el usuario está logueado, chequeamos si está visto o no
    if(Master::$id !== null) { $see1 = ', v.mode AS see'; $see2 = 'LEFT JOIN '.$this->db->prefix.'temas_visitas AS v ON v.user_id = ? && v.tema_id = tema_id'; $array= array(Master::$id); }
    else { $see1 = ''; $see2 = ''; $array = array(); }

    // Filtramos por usuario...
    if($uid !== null) { $where.= ' && t.user_id = ?'; $array[] = $uid; }

    // Estamos buscando una clave específica..
    if($key !== null) { $where.= ' && (t.titulo LIKE \'%'.$key.'%\' || t.contenido LIKE \'%'.$key.'%\')'; }

    // Enviamos la consulta
    $data = $this->db->query('SELECT t.id AS tema_id, t.foro_id, t.titulo, t.estado, t.fijado, t.user_id, t.prefijo, t.fechahora, t.ultima_respuesta, t.respuestas, t.visitas, c.nombre, c.color, c.icono, u.nick, r.nombre AS rango, f.nombre AS foro'.$see1.' FROM '.$this->db->prefix.'temas AS t LEFT JOIN '.$this->db->prefix.'temas_tipos AS c ON c.id = t.tipo LEFT JOIN '.$this->db->prefix.'usuarios AS u ON u.id = t.user_id LEFT JOIN '.$this->db->prefix.'rangos AS r ON r.id = u.rango_id '.$see2.' LEFT JOIN '.$this->db->prefix.'foros AS f ON t.foro_id = f.id '.$where.' GROUP BY t.id ORDER BY '.$order.' LIMIT '.$limits[0].', '.$limits[1], $array, false);
    if($data !== false)
     {
      $t = 0;
      $array = array();
      // Armamos el arreglo final
      while($topic = $data->fetchrow())
       {
        // Si no está esta variable, es porque el usuario no está logueado,
        // entonces lo marcamos como nuevo
        if(!isset($topic['see'])) { $topic['see'] = self::TOPIC_I_UNREAD; }
        $array[$t] = $topic;
        $t = ++$t;
       }

      return array('list' => $array, 'cant' => $data->numrows());
     }
    else { return false; }
   } // public function get_topics();



  /**
   * Creamos un nuevo tema
   * @param int $foro_id ID del foro
   * @param int $tipo ID del prefijo del tema
   * @param int $icono ID del icono que usará el tema
   * @param string $titulo Título del tema
   * @param text $contenido Contenido del tema
   * @param array $config Arreglo con las propiedades del tema array('fijar', 'comentar', 'firmas', 'prefijos');
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function new_topic($foro_id, $tipo, $titulo, $contenido, $config)
   {
    $parser = new Parser($this->db);
    $contenido = htmlspecialchars($contenido);
    $insert = $this->db->insert('temas', array(
     'foro_id' => $foro_id,
     'user_id' => Master::$id,
     'fijado' => $config['fijar'],
     'tipo' => (int) $tipo,
     'titulo' => htmlspecialchars($titulo),
     'contenido' => $contenido,
     'contenido_html' => $parser->parse($contenido),
     'estado' => ((Grades::is_access(Grades::USER_NO_NEED_APROBATION) === true) ? self::TOPIC_STATUS_APPROBED : self::TOPIC_STATUS_UNNAPPROBED),
     'prefijo' => $config['prefijos'],
     'comentar' => $config['comentar'],
     'firmas' => $config['firmas'],
     'ip' => ip2long(getip()),
     'fechahora' => time(),
     ), true);
    if($insert !== false)
     {
      // actualizamos la cantidad de temas en general
      $this->db->query('UPDATE '.$this->db->prefix.'estadisticas SET temas = temas + 1 WHERE  id = 1 ', null, false);
      // actualizamos la cantidad de temas del foro
      $this->db->query('UPDATE '.$this->db->prefix.'foros SET temas = temas + 1 WHERE id = ?', $foro_id, false);
      // actualizamos la cantidad de temas del usuario
      $this->db->query('UPDATE '.$this->db->prefix.'usuarios SET mensajes = mensajes + 1 WHERE  id = ?', Master::$id, false);

      // Actualizamos los archivos de cache
      if($config['fijar'] == 1) { Cache::delete('forum_'.$foro_id.'_sticks'); }
      Cache::delete('forum_'.$foro_id.'_data');
      return true;
     } else { return false; }
   } // public function new_topic();



  /**
   * Obtener las respuestas a un tema
   * @param int $id ID del tema objetivo
   * @param int $page Nro de página
   * @param int $limit Respuestas mostradas por página
   * @param boolean $full Indica si cargar los mensajes ocultos o no
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function get_answers($id, $page, $limit, $full = false)
   {
    $limits = paginate($page, $limit);
    if($full == false)
     {
      $where = '&& r.estado = ?';
      $array = array($id, self::TOPIC_STATUS_APPROBED);
     }
    else
     {
      $where = '';
      $array = $id;
     }
    return $this->db->query('SELECT r.id, r.user_id, r.estado, r.contenido, r.contenido_html, r.fechahora, r.ip, u.nick, u.nombre, u.avatar, u.rango_id, u.mensajes, u.sexo, u.firma_html, u.fecharegistro, l.nombre AS rango, l.icono AS rango_icono, l.color, l.bold FROM '.$this->db->prefix.'temas_respuestas AS r LEFT JOIN '.$this->db->prefix.'usuarios AS u ON u.id = r.user_id LEFT JOIN '.$this->db->prefix.'rangos AS l ON l.id = u.rango_id WHERE r.tema_id = ? '.$where.' ORDER BY r.fechahora LIMIT '.$limits[0].', '.$limits[1], $array, false);
   } // public function get_answers();



  /**
   * Crear una nueva respuesta a un tema
   * @param int $topic ID del tema a responder
   * @param string $title Título de la respuesta (omitido en respuesta rápida)
   * @param string $content Contenido de la respuesta
   * @param boolean $allow Configuración, si se puede aprobar este comentario o no
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function new_answer($topic, $content)
   {
    $data = $this->db->query('SELECT id, comentar FROM temas WHERE id = ? LIMIT 1', $topic, true);
    if($data == false || !$data) { return 0; } // Tema inexistente o no se puede comentar
    elseif($data['comentar'] == '0') { return 2; }
    else
     {
      $parser = new Parser($this->db);
      $res = $this->db->insert('temas_respuestas', array(
       'user_id' => Master::$id,
       'tema_id' => $topic,
       'estado' => (Grades::is_access(Grades::USER_NO_NEED_APROBATION) == true) ? self::TOPIC_STATUS_APPROBED : self::TOPIC_STATUS_UNNAPPROBED,
       'contenido' => htmlspecialchars($content),
       'contenido_html' => $parser->parse(htmlspecialchars($content)),
       'fechahora' => time(),
       'ip' => ip2long(getip())
       ), true);
      if(!$res || $res == false) { return false; }
      else
       {
        // actualizamos las estadisticas
        $this->db->query('UPDATE '.$this->db->prefix.'temas SET respuestas = respuestas + 1, ultima_respuesta = '.(int) $res.' WHERE id = ?', $topic, false);
        $this->db->query('UPDATE '.$this->db->prefix.'estadisticas SET respuestas = respuestas + 1 WHERE  id = 1', null, false);
        return true;
       }
     }
   } // public function new_answer()



  /**
   * Obtener una respuesta
   * @param int $id ID del tema objetivo
   * @return array
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function get_answer($id)
   {
    return $this->db->query('SELECT r.id, r.tema_id, r.user_id, r.estado, r.contenido_html, r.fechahora, r.ip, u.nick, u.nombre, u.avatar, u.rango_id, u.mensajes, u.sexo, u.firma_html, u.fecharegistro, l.nombre AS rango, l.icono, l.color, l.bold FROM '.$this->db->prefix.'temas_respuestas AS r LEFT JOIN '.$this->db->prefix.'usuarios AS u ON u.id = r.user_id LEFT JOIN '.$this->db->prefix.'rangos AS l ON l.id = u.rango_id WHERE r.id = ? ORDER BY r.fechahora LIMIT 1', $id, true);
   } // public function get_answer();



  /**
   * Mostrar una respuesta
   * @param int $id ID de la respuesta
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function show_answer($id)
   {
    //TODO: agregar historial
    return $update = $this->db->update('temas_respuestas', array('estado' => self::TOPIC_STATUS_APPROBED), array('id' => $id), false);
   } // public function hide_answer();



  /**
   * Esconder una respuesta
   * @param int $id ID de la respuesta
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function hide_answer($id)
   {
    //TODO: agregar historial
    return $update = $this->db->update('temas_respuestas', array('estado' => self::TOPIC_STATUS_HIDDED), array('id' => $id), false);
   } // public function hide_answer();



  /**
   * Borrar una respuesta
   * @param int $id ID de la respuesta
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function delete_answer($id)
   {
    //TODO: agregar historial
    $update = $this->db->delete('temas_respuestas', array('id' => $id),false);
    if($update !== false)
     {
      $this->db->query('UPDATE '.$this->db->prefix.'temas SET respuestas = respuestas - 1 WHERE id = ? && respuestas >= 1', $id, false);
      $this->db->query('UPDATE '.$this->db->prefix.'estadisticas SET respuestas = respuestas - 1 WHERE id = 1', null, false);
      return true;
     }
    else { return false; }
   } // public function delete_answer();



  /**
   * Marcar un tema como visitado
   * @param int $tid ID del tema objetivo
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function visit_topic($tid)
   {
    // verificamos que no se haya visitado previamente
    $query = $this->db->query('SELECT id FROM '.$this->db->prefix.'temas_visitas WHERE user_id = ? && tema_id = ? LIMIT 1', array(Master::$id, $tid), true);
    // dependiendo del resultado marcamos como visitado o no.
    if(!$query || $query == false)
     {
      $ins = $this->db->insert('temas_visitas', array('tema_id' => $tid, 'user_id' => Master::$id, 'fechahora' => time()), false);
      if($ins !== false) { $this->db->query('UPDATE '.$this->db->prefix.'temas SET visitas = visitas + 1 WHERE id = ?', $tid, false); }
     }
    else { return false; }
   } // public function visit_topic();



  /**
   * Habilitar los comentarios de un tema
   * @param int $tid ID del tema objetivo
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function enable_coments($tid)
   {
    //TODO: agregar historial
    return $update = $this->db->update('temas', array('comentar' => '1'), array('id' => $tid), false);
   } // public function enable_coments();



  /**
   * Inhabilitar los comentarios de un tema
   * @param int $tid ID del tema objetivo
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function disable_coments($tid)
   {
    //TODO: agregar historial
    return $update = $this->db->update('temas', array('comentar' => '0'), array('id' => $tid), false);
   } // public function disable_coments();



  /**
   * Fijar un tema
   * @param int $tid ID del tema objetivo
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function stick($tid)
   {
    //TODO: agregar historial
    return $update = $this->db->update('temas', array('fijado' => '1'), array('id' => $tid), false);
   } // public function stick();



  /**
   * Des-fijar un tema
   * @param int $id ID del tema objetivo
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function un_stick($tid)
   {
    //TODO: agregar historial
    return $update = $this->db->update('temas', array('fijado' => '0'), array('id' => $tid), false);
   } // public function un_stick();



  /**
   * Borrar un tema
   * @param int $tid ID del tema objetivo
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function delete_topic($tid)
   {
    //TODO: agregar historial
    return $this->db->update('temas', array('estado' => self::TOPIC_STATUS_HIDDED), array('id' => $tid), false);
   } // public function delete_topic();



  /**
   * Mover un tema
   * @param int $tid ID del tema objetivo
   * @param int $fid ID del foro objetivo
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function move_topic($tid, $fid)
   {
    //TODO: agregar historial
    //TODO: agregar tema vinculado "movido a:"
    $this->db->query('UPDATE temas SET temas = temas + 1 WHERE id = ? LIMIT 1', $fid, false);
    $this->db->query('UPDATE temas SET temas = temas - 1 WHERE id = ? LIMIT 1', $fid, false);
    return $this->db->update('temas', array('foro_id' => $fid), array('id' => $tid), false);
   } // public function move_topic();



  /**
   * Obtener los prefijos de temas
   * @return array Arreglo con la lista de prefijos
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function get_types()
   {
    $query = $this->db->query('SELECT * FROM '.$this->db->prefix.'temas_tipos ORDER BY nombre', null, false);
    if(!$query || $query == false) { return false; }
    else
     {
      $types = array();
      while($type = $query->fetchrow()) { $types[] = $type; }
      return $types;
     }
   } // public function get_types();
 } // class temas();