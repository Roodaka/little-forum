<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');
// Asignamos el título a la página
//$view->add_key('web_title', $lang['']);

$func = (isset($_GET['f'])) ? $_GET['f'] : 'all';
$page = (isset($_GET['p'])) ? (int) $_GET['p'] : 1;

$msn = new Messenger($db, Master::$id);

if($func === 'unread')
 {
  $msn->get_messages('send');
 }
elseif($func === 'all')
 {

 }
elseif($func === 'send')
 {

 }
elseif($func === 'read' && isset($_GET['v']))
 {

 }
/*else()
 {

 }*/