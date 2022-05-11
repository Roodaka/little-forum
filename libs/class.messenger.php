<?php

class Messenger
 {
  protected $db;
  protected $id = null;
  const READED = 1;
  const UNREAD = 0;
  const DELETED = 1;
  const SPAM = 5;



  /**
   * Constructor de la clase
   * @param object $db Instancia de LittleDB
   * @param object $user ID del usuario activo
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function __construct($db, $user)
   {
    $this->id = $user;
    $this->db = $db;
   } // public function __construct();



  /**
   * Obtenemos una lista de mensajes
   * @param string Filtrado
   * @param int $page Nro de página
   * @param int $limit Límite de mensajes por página
   * @return array Lista de mensajes
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function get_messages($filter = 'unread', $uid = null, $page = 1, $limit = 30)
   {
    if($filter == 'unread') { $target = 'u.id = m.autor_id'; $where = 'm.user_id = ? && m.leido = 0'; }
    elseif($filter == 'received') { $target = 'u.id = m.autor_id'; $where = 'm.user_id = ? && m.leido = 1'; }
    elseif($filter == 'send') { $target = 'u.id = m.user_id'; $where = 'm.autor_id = ?'; }
    $pager = paginate($page, $limit);
    $query = $this->db->query('SELECT u.nick, m.autor_id, m.user_id, m.titulo, m.leido, m.fechahora, m.ip FROM '.$this->db->prefix.'mensajes AS m LEFT JOIN '.$this->db->prefix.'usuarios AS u ON '.$target.' WHERE '.$where.' && (m.borrado_autor != 1 && m.borrado_receptor !=1) LIMIT '.$pager[0].', '.$pager[1], $this->id, false);
    if(!$query || $query == false) { return false; }
    else
     {
      $result = array();
      while($row = $query->fetchrow())
       {
        $result[] = $row;
       }
      return $result;
     }
   } // public function get_messages();



  /**
   * Obtenemos los mensajes de una conversación con X usuario
   * @param int $uid ID del usuario Objetivo
   * @param int $page Nro de página
   * @param int $limit Límite de mensajes por página
   * @return array Lista de mensajes
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function get_conversation($uid, $page = 1, $limit = 30)
   {
    $pager = paginate($page, $limit);
    $query = $this->db->query('SELECT u.nick, m.* FROM '.$this->db->prefix.'mensajes' , array($uid, $this->id), false);
   } // public function get_conversation();



  /**
   * Obtenemos un mensaje
   * @param int $mid ID del mensaje objetivo
   * @return array Arreglo asociativo con los datos del mensaje
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function get_message($mid)
   {
    return $this->db->query('SELECT * FROM '.$this->db->prefix.'mensajes WHERE id = ? LIMIT 1', $mid, true);
   } // public function get_message();



  /**
   * Marcamos X mensaje como leído
   * @param int $mid ID del mensaje objetivo
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function mark_as_read($mid)
   {
    return $this->db->update('mensajes', array('leido' => self::READED), array('id' => (int) $mid), false);
   } // public function mark_as_read();



  /**
   * Marcar X mensaje como no leído
   * @param int $mid ID del mensaje objetivo
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function mark_as_unread($mid)
   {
    return $this->db->update('mensajes', array('leido' => self::READED), array('id' => (int) $mid), false);
   } // public function mark_as_unread();



  /**
   * Marcar X mensaje como SPAM
   * @param int $mid ID del mensaje objetivo
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function mark_as_spam($mid)
   {
    return $this->db->update('mensajes', array('leido' => self::SPAM), array('id' => (int) $mid), false);
   } // public function mark_as_unread();



  /**
   *
   * @param int $mid ID del mensaje objetivo
   * @param
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function delete($mid, $mode = '')
   {
    if($mode === 'autor')
     {
      return $this->db->update('mensajes', array('autor_id' => $this->id), array('borrado_autor' => self::DELETED), false);
     }
    elseif($mode === 'user')
     {
      return $this->db->update('mensajes', array('user_id' => $this->id), array('borrado_receptor' => self::DELETED), false);
     }
   } // public function delete();



  /**
   * Enviar un nuevo mensaje
   * @param int $uid ID de Usuario Objetivo
   * @param string $content Contenido del mensaje
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function new_message($uid, $content, $title = null)
   {
    $parser = new Parser($this->db);
    $content = htmlspecialchars($content);
    $return = $this->db->insert('mensajes', array(
     'autor_id' => (int) $this->id,
     'user_id' => (int) $uid,
     'titulo' => (($title !== null) ? htmlspecialchars($title) : ''),
     'contenido' => $content,
     'contenido_html' => $parser->parse($content),
     'borrado_autor' => 0,
     'borrado_receptor' => 0,
     'leido' => self::UNREAD,
     'fechahora' => time(),
     'ip' => ip2long(getip())
     ), false);
    if($return !== false) { return true; }
    else { return false; }
   } // public function new_message();
 } // Class Messenger