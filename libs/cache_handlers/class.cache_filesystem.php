<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');

class Cache_FileSystem
 {
  // Nombre de la tabla Cache
  protected $location = '';

  // Duración de la cache dinamica
  protected $expire_time = 0;



  public function __construct($location, $expire_time)
   {
    $this->location = $location;
    $this->expire_time = $expire_time;

    if(!is_writable(LFS_ROOT.$this->location)) { return false; }
    else { return true; }

   } // public function __construct();



  public function set($key, $value, $expires)
   {
    // Definimos variables
    $key = strtolower($key);
    if($expires == null) { $expires = $this->expire_time; }
    $array = array(
     'cache_data' => $value,
     'cache_expires' => (int) $expires,
     'cache_time' => time());

    $content = '<?php defined(\'LFS_ROOT\') or exit(\'No tienes Permitido el acceso.\'); /** LFS Cache */ return \''.serialize($array).'\';';
    // Abrimos el archivo
    $file = fopen(LFS_ROOT.$this->location.$key.'.php', 'w') or exit('No se pudo abrir/crear el archivo "cache/data/'.$key.'.php"');
    // Escribimos
    if(!fwrite($file, $content)) { exit('No se pudo escribir el archivo "'.$this->location.$key.'.php"'); }
    else
     {
      fclose($file);
      return true;
     }
   } // public function set();



  public function get($key)
   {
    $key = strtolower($key);
    // Incluimos y retornamos el archivo
    if($this->is_set($key))
     {
      $data = unserialize(require(LFS_ROOT.$this->location.$key.'.php'));
      // Si todavía le queda 'vida' lo cargamos
      if($data['cache_time'] > (time() - $data['cache_expires']))
       {
        return $data['cache_data'];
       }
      else
       {
        return false;
       }
     }
    else
     {
      return false;
     }
   } // public function get();



  public function is_set($key)
   {
    return (is_file(LFS_ROOT.'cache/data/'.$key.'.php')) ? true : false;
   } // public function is_set();



  public function delete($key)
   {
    if($this->is_set($key) === true)
     {
      return unlink(LFS_ROOT.$this->location.$key.'.php');
     }
    else { return true; }
   } // public function delete($id)



  // Tamaño de la Cache
  public function size()
   {
    $size = 0;
    $dir = opendir(LFS_ROOT.$this->location) or exit('No se pudo abrir el directorio '.LFS_ROOT.$this->location);
    while(($file = readdir($dir)) !== false)
     {
      if($file !== '.' || $file !== '..')
       {
        $size += filesize(LFS_ROOT.$this->location.$file);
       }
     }
    closedir($dir);
    return $size;
   } // public function size();



  // Limpiar Cache
  public function clear()
   {
    $dir = opendir(LFS_ROOT.$this->location) or exit('No se pudo abrir el directorio '.LFS_ROOT.$this->location);
    while(($file = readdir($dir)) !== false)
     {
      if($file !== '.' || $file !== '..')
       {
        unlink(LFS_ROOT.$this->location.$file);
       }
     }
    closedir($dir);
   } // public function clear();
 } // class Cache_FileSystem();