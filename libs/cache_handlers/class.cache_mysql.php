<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');

class Cache_MySQL
 {
  // Instancia de LittleDB
  protected $db = null;

  // Nombre de la tabla Cache
  protected $table_name = 'cache';

  // Duración de la cache dinamica
  protected $expire_time = 0;
  
  // Tipos de Cache
  const TYPE_NORMAL = 0; // Dinamica
  const TYPE_STATIC = 1; // Estatica



  public function __construct($db, $table_name, $expire_time)
   {
    $this->db = $db;
    $this->table_name = $table_name;
    $this->expire_time = $expire_time;
   } // public function __construct();



  public function set($key, $value, $expires)
   {
    $key = strtolower($key);
    if($expires == null) { $expires = $this->expire_time; }
    $check = $query = $this->db->query('SELECT fechahora FROM '.$this->db->prefix.$this->table_name.' WHERE clave = ?  LIMIT 1', $key, true);
    if(!$check || $check == false)
     {
      return $this->db->insert($this->table_name, array(
       'clave' => $key,
       'contenido' => serialize($value),
       'tipo' => $expires,
       'fechahora' => time()
       ));
     }
    else
     {
      return $this->db->update($this->table_name, array(
       'contenido' => serialize($value),
       'fechahora' => time()), array('clave' => strtolower($key)),false);
     }
   } // public function set();



  public function get($key)
   {
    $query = $this->db->query('SELECT contenido FROM '.$this->db->prefix.$this->table_name.' WHERE clave = ? && (tipo = ?) || (tipo = ? && fechahora < ?) LIMIT 1', array(strtolower($key), self::TYPE_STATIC, self::TYPE_NORMAL, (time() - $this->expire_time)), true);
    if($query !== false)
     {
      return unserialize($query['contenido']);
     }
    else
     {
      return false;
     }
   } // public function get();



  public function is_set($key)
   {
    $query = $this->db->query('SELECT fechahora FROM '.$this->db->prefix.$this->table_name.' WHERE clave = ?  LIMIT 1', $key, true);
    if(isset($query['fechahora'])) { return true; }
    else { return false; }
   }


  // Tamaño de la Cache
  public function size() { }


  // Limpiar Cache
  public function clear($key = null)
   {
    if($key == null)
     {
      return $this->db->query('TRUNCATE TABLE '.$this->table_name, null, false);
     }
    else
     {
      return $this->db->delete($this->table_name, 'clave = '.$key, false);
     }
   }
 } // class Cache_MySQL();