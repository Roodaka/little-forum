<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');

class Cache
 {
  protected static $handler = null;


  /**
   * Instanciamos la clase
   * @param string $type Tipo de Cache
   * @param int $expire_time Vida de la cache
   * @param string $location Directorio o Tabla del cache
   * @param object $db Instancia de la DB si es necesaria
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public static function Load($type = '', $expire_time = 0, $location = 'cache', $db = null)
   {
    if($type === 'file')
     {
      require('cache_handlers/class.cache_filesystem.php');
      self::$handler = new Cache_FileSystem($location, $expire_time);
     }
    elseif($type === 'mysql')
     {
      require('cache_handlers/class.cache_mysql.php');
      self::$handler = new Cache_MySQL($db, $location, $expire_time);
     }
    elseif($type === 'xcache')
     {
      require('cache_handlers/class.cache_xcache.php');
      self::$handler = new Cache_Xcache($expire_time);
     }
    elseif($type === 'apc')
     {
      require('cache_handlers/class.cache_apc.php');
      self::$handler = new Cache_APC($expire_time);
     }
    elseif($type === 'memcached')
     {
      require('cache_handlers/class.cache_memcached.php');
      self::$handler = new Cache_Memcached($location, $expire_time);
     }

    if(self::$handler == false || self::$handler == null)
     {
      require('cache_handlers/class.cache_null.php');
      self::$handler = new Cache_Null();
     }
   } // public function __construct();



  public static function set($key, $value, $expires = null)
   {
    return self::$handler->set($key, $value, $expires);
   } // public function set();



  public static function get($key)
   {
    return self::$handler->get($key);
   } // public function get();



  public static function is_set($key)
   {
    return self::$handler->is_set($key);
   } // public function is_set();



  public static function delete($key)
   {
    return self::$handler->delete($key);
   } // public function clear();




  public static function size()
   {
    return self::$handler->size();
   } // public function size();



  public static function clear()
   {
    return self::$handler->clear();
   } // public function clear();
 } // class Cache_MySQL();