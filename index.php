<?php
$timestart = microtime(true);
$memstart = memory_get_usage();

// Directorio del script
define('LFS_ROOT', dirname(__FILE__).'/');

// Versión del script
define('VERSION', 'v0.1.2');

// Definimos el estado del servidor, setear FALSE para modo producción y TRUE
// para desarrollo
define('DEV', true);

// Mostramos los errores
if(DEV === true)
 {
  error_reporting(E_ALL);
  ini_set('display_errors', true);
 }

// Configuraciones
$config = require(LFS_ROOT.'libs/configs.db.php');

// Funciones Varias
require(LFS_ROOT.'libs/functions.php');

// Iniciamos el proceso de carga automatica de librerias.
spl_autoload_register('autoload');

// Inicializamos y Conectamos a la Base de datos
$db = new LittleDB($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name'], $config['db_pref']);

// Iniciamos Master, Grades y View, las clases maestras.
$master = new Master($db);
$grades = new Grades($db, Master::$data['rango_id']);
$view = new View();

// Inicializamos el Cache
Cache::Load(Master::$config['cache_mode'], Master::$config['cache_life'], 'cache/data/', $db);

$_SESSION['lfs_error'] = null;
$view->add_key('data', Master::$data);
$view->add_key('config', Master::$config);

require(LFS_ROOT.'themes/'.Master::$data['config_tema'].'/data.php');
$lang = $master->get_languaje_data();
$view->add_key('lang', $lang['vars']);
$view->add_key('langdata', $lang['data']);
$view->add_key('theme', $theme);
$lang = $lang['vars'];
// Si estamos realizando una acción en particular...
if(isset($_GET['a'])) { $a = (string) $_GET['a']; }
else
 {
  // No hay acción, chequeamos si el usuario puede ver la home y lo llevamos
  // ahí o al login
  $a = (Grades::is_access(Grades::USER_SEE_HOME)) ? 'home' : 'login';
 }
// Cargamos las rutas
$routes = require(LFS_ROOT.'libs/configs.routes.php');
         http://dribbble.com/creativemints/projects/84076-UI-Kits
// si el módulo existe
if(isset($routes[$a]))
 {
  // Si el archivo existe
  if(is_file('pages/'.$routes[$a][0]))
   {
    // si tiene acceso
    if(!Grades::is_level($routes[$a][1]))
     {
      $_SESSION['lfs_error'] = 'access';
     }
   } else { $_SESSION['lfs_error'] = 'file'; }
 } else { $_SESSION['lfs_error'] = 'modulo'; }

// Cargamos la cabecera, el módulo y el pie
require(LFS_ROOT.'pages/header.php');
require(LFS_ROOT.'pages/'.(($_SESSION['lfs_error'] === null) ? $routes[$a][0] : $routes['result'][0]));
require(LFS_ROOT.'pages/footer.php');