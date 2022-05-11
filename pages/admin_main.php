<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');

// creamos la instancia de la clase admin
$admin = new Admin($db);

// verificamos si existe el target sino lo establecemos en 'home'
$target = isset($_GET['f']) ? $_GET['f'] : 'home' ;

// si el target es home
if($target=='home')
 { 
  // Comprobamos que hayan actualizaciones
  //$rain->assign('updates',$admin->check_updates());
  // establecemos la accion en inicio
  $data['target'] = 'inicio';
 }
elseif($target == 'news')
 {
  // establecemos el target
  $data['target'] = 'novedades';
 }
elseif($target == 'creditos')
 {
  // establecemos el target
  $data['target'] = 'creditos';
 }
else
 {
  // Error 404
 // redirect('result', '404');
 }
 
// asignamos la data al rain
$view->add_key('datos', $data);

// Dibujamos
$view->add_template('admin');