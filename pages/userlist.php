<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');

$cuenta = new Cuenta($db);

$view->add_key('web_title', $lang['head_menu_userlist']);

$view->add_key('userlist', $cuenta->get_users(1, Master::$config['pagelimit_users']));

$view->add_template('userlist', false);