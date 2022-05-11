<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');

class Reportes
 {
  protected $db = null;
  protected $userid = 0;

  public function __construct($db, $id)
   {
    $this->db = $db;
    $this->userid = $id;
   }


  public function report($type, $targetid)
   {
    return $this->db->insert('reportes', array(
     '' => '',
     '' => '',
     '' => '',
     'fechahora' => time(),
     'ip' => ip2long(getip()),
     ));
   }
 } // class Denuncias();
?>
