<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');
// Cargamos las estadísticas de la web.
if(isset($_COOKIE[Master::$config['cookie_name']])) { $cookie = 'true'; }
else { $cookie = 'false'; }
$stats = array(
 'cache' => Cache::size(),
 'cookie' => $cookie,
 'dbcount' => $db->count,
 'ram' => $memstart,
 'time' => $timestart);
// ============================ Liberamos Memoria =========================== //
if(isset($foros)) { unset($foros); }
if(isset($registro)) { unset($registro); }
if(isset($cuenta)) { unset($cuenta); }
if(isset($temas)) { unset($temas); }
unset($master);
unset($db);
// ============================ Liberamos Memoria =========================== //

$view->add_key('stats', $stats);
$view->add_template('footer', false);

$view->show(false);

unset($view);