<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');
class Cache_Null
 {
  public function __construct() { }
  public function set() { return false; }
  public function get() { return false; }
  public function is_set() { return false; }
  public function delete() { return false; }
  public function size() { return false; }
  public function clear() { return false; }
 } // class Cache_Null();