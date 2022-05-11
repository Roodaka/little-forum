<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');

class Noticias
 {
  // Instancia de LittleDB
  protected $db;

  public function __construct($db)
   {
    // Guardamos a LittleDB
    $this->db = $db;
   } // public function __construct();

  public function get_lasts($limit = 1)
   {
    return $this->db->query('SELECT contenido, fechahora FROM '.$this->db->prefix.'noticias ORDER BY fechahora ASC LIMIT 0, '.(int) $limit, null, false);
   }

  public function make_new($id, $content)
   {
    return $this->db->insert('noticias', array(
    'contenido' => htmlspecialchars($content),
    'user_id' => (int) $id,
    'fechahora' => time()));
   }

 } // class Noticias();