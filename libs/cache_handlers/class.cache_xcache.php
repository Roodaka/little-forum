<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');
class Cache_Xcache
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
    return xcache_set($key, serialize($value), $expires);
   } // public function set();



  public function get($key)
   {
    return ($this->is_set($key)) ? unserialize(xcache_get($key)) : false;
   } // public function get();



  public function is_set($key)
   {
    return xcache_isset($key);
   } // public function is_set();



  public function delete($key)
   {
    return (($this->is_set($key) === true) ? xcache_unset($key) : false);
   } // public function delete($key)



  // Tamaño de la Cache
  public function size()
   {

   } // public function size();



  // Limpiar Cache
  public function clear()
   {

   } // public function clear();
 } // class Cache_Xcache();