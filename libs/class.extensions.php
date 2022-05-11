<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');
// Clase para manejo de contenido externo (plugins, temas, emoticones, idiomas, etc)

class Extensions
 {
  public static function get_theme($target = null) { }
  public static function list_themes($default = 'default') { }

  public static function get_languaje($target = null) { }
  public static function list_languajes($default = 'es') { }

  public static function get_smiles($target = null) { }
 } // Class Extensions();