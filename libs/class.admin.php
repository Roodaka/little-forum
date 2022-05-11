<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');
class Admin
 {
  /**
   * Instancia de LittleDB.
   */
  protected $db = null;

  /**
   * Url del servidor de datos
   */
  protected $server = 'http://server.littleforum.com.ar/';

  /**
   * Datos de la pagina
   */
  protected $web;



  /**
   * Constructor de la clase
   * @param object $db Instancia de LittleDB
   * @author Cody Roodaka <roodakazo@hotmail.com
   */
  public function __construct($db)
   {
    $this->db = $db;
    //TODO: armar los datos de la web (servidor, ip, hash, url, etc)
   }



  /**
   * Cambiar un valor en la configuraci�n del servidor
   * @param string $key nombre de la configuraci�n
   * @param int|string $value Valor de la nueva configuraci�n
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function update_config($key, $value)
   {
    return $this->db->update('config', array('valor' => $value), array('clave' => $key), false);
   } // public function update_config();
 } // class Admin();