<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');

class Cache_Memcached
 {
  // Duración de la cache dinamica
  protected $expire_time = 0;
  private $memcached = null;



  public function __construct($server, $expire_time)
   {
    $this->memcached = new Memcached;
    $this->expire_time = $expire_time;

    // Agregamos el servidor de Memcached
    $this->memcached->addServer($server, 11211, 1);
   } // public function __construct();



  public function set($key, $value, $expires)
   {
    if($expires == null) { $expires = $this->expire_time; }
    return $this->memcached->add($key, array(serialize($value), time(), $expires), $expires);
   } // public function set();



  public function get($key)
   {
    $data = $this->memcached->get($key);
		return (is_array($data)) ? unserialize($data[0]) : false;
   } // public function get();



  public function is_set($key)
   {
    return (in_array($key, $this->memcached->getAllKeys())) ? true : false;
   } // public function is_set();



  public function delete($key)
   {
    return $this->memcached->delete($key);
   } // public function delete($id)



  // Tamaño de la Cache
  public function size()
   {
    $size = $this->memcached->getStats();
    return (int) $size['bytes'];
   } // public function size();



  // Limpiar Cache
  public function clear($key = null)
   {
    return $this->memcached->flush();
   } // public function clear();
 } // class Cache_Memcached();