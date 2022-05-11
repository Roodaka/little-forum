<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');
// Asignamos el título a la página
$view->add_key('web_title', $lang['head_menu_lasttopics']);

// Auto marcamos la página
if(isset($_GET['p'])) { $page = (int) $_GET['p']; }
else { $page = 1; }

// Buscamos..
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) { $key = $_POST['search']; }
else { $key = null; }
// Filtramos por autor
if(isset($_GET['v'])) { $uid = (int) $_GET['v']; }
else { $uid = null; }

$foros = new Foros($db);
$temas = new Temas($db);

// Cargamos los temas
$topics = $temas->get_topics('t.fechahora DESC', $page, Master::$config['pagelimit_topics'], null, false, $uid, $key);
if($topics !== false) { $view->add_key('temas', $topics['list']); }
else { $view->add_key('temas', false); }

// Calculamos el paginado.
$paginator = new Paginator($topics['cant'], Master::$config['pagelimit_topics'], Master::$config['pagelimit_nodes']);

// Arbol de foros
$view->add_key('lb_tree', $foros->get_forums_tree(false));

// Paginado
$view->add_key('paginado', $paginator->paginate($page));

$view->add_template('leftbar');
$view->add_template('topics');