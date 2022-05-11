<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');

class Cache_APC
 {
  // Duración de la cache dinamica
  protected $expire_time = 0;



  public function __construct($expire_time)
   {
    $this->expire_time = $expire_time;
   } // public function __construct();



  public function set($key, $value, $expires)
   {
    if($expires == null) { $expires = $this->expire_time; }
    return apc_store($key, array(serialize($value), time(), $expires), $expires);
   } // public function set();



  public function get($key)
   {
    $data = apc_fetch($key);
		return (is_array($data)) ? unserialize($data[0]) : false;
   } // public function get();



  public function is_set($key)
   {
    return apc_exists($key);
   } // public function is_set();



  public function delete($key)
   {
    return apc_delete($key);
   } // public function delete($id)



  // Tamaño de la Cache
  public function size()
   {
    // TODO: acá poner algo.
    // http://www.php.net/manual/es/function.apc-cache-info.php
   } // public function size();



  // Limpiar Cache
  public function clear($key = null)
   {
    return apc_clear_cache('user');
   } // public function clear();
 } // class Cache_APC();