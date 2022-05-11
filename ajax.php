<?php
// Directorio del script
define('LFS_ROOT', dirname(__FILE__).'/');

// Versión del script
define('VERSION', 'v0.1.2');

// Configuraciones
$config = require(LFS_ROOT.'libs/configs.db.php');

// Funciones Varias
require(LFS_ROOT.'libs/functions.php');

// Iniciamos el proceso de carga automatica de librerias.
spl_autoload_register('autoload');

// Inicializamos y Conectamos a la Base de datos
$db = new LittleDB($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name'], $config['db_pref']);

// Iniciamos Master
$master = new Master($db);
$grades = new Grades($db, Master::$data['rango_id']);

if(isset($_GET['a']))
 {
  $routes = require(LFS_ROOT.'libs/configs.ajax.php');
  if(Master::$config['site_maintenance'] == false)
   {
    if(isset($routes[$_GET['a']]))
     {
      if(is_file(LFS_ROOT.'ajax/'.$routes[$_GET['a']][0]))
       {
        require(LFS_ROOT.'ajax/'.$routes[$_GET['a']][0]);
       } else { exit('file'); }
     } else { exit('undefined'); }
   } else { exit('maintenance'); }
 }