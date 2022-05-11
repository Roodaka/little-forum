<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');
// Arreglo con la lista de errores posibles
$data = array('400', '403', '404', '408', '500', '503', '505', 'access', 'exit', 'file', 'ipban', 'modulo','mysql','mysqldb');

// Si no hay error, por qué estamos aquí?
if(!isset($_SESSION['lfs_error']) || !in_array($_SESSION['lfs_error'], $data)) { $_SESSION['lfs_error'] = ((isset($_GET['v'])) ? $_GET['v'] : 'wtf'); }

// Asignamos el título y el error a la plantilla
$view->add_key('web_title', $lang['error_title']);
$view->add_key('result', $_SESSION['lfs_error']);
$view->add_template('result');

// Retornamos el valor del error a nulo, ya que fue mostrado.
$_SESSION['lfs_error'] = null;