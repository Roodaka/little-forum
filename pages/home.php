<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');

$cuenta = new Cuenta($db);
$foros = new Foros($db);
$temas = new Temas($db);

// Cargamos los últimos temas creados
$topics = $temas->get_topics('t.fechahora DESC', 1, 5, null, false);
if($topics !== false) { $view->add_key('lb_topics', $topics['list']); }
else { $view->add_key('lb_topics', 'false'); }

// Cargamos el Árbol de foros para la portada
$view->add_key('foros', $foros->get_forums_tree(true));
// Cargamos la lista de usuarios conectados
$view->add_key('online_users', $cuenta->get_online_list(50));

// Dibujamos las plantillas
$view->add_template('leftbar');
$view->add_template('home');