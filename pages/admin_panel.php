<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');
// Preparamos arreglos para ser recorridos por bucles, así encontrar el
// seleccionado y marcarlo.
$news_toshow = array(1, 2, 3, 4, 5);
$approbe_types = array('none', 'mail', 'admin');


if($_SERVER['REQUEST_METHOD'] == 'POST')
 {
  // Instanciamos Admin.
  $admin = new Admin($db);

  // Vamos actualizando las configuraciones
  // Firmas
  $admin->update_config('enable_sign',isset($_POST['enable_sign']) ? true : false);
  $admin->update_config('sign_bbc',isset($_POST['sign_bbc']) ? true : false);
  $admin->update_config('sign_images',isset($_POST['sign_images']) ? true : false);
  $admin->update_config('sign_maxchars',(int) $_POST['sign_maxchars']);
  $admin->update_config('sign_smiles',isset($_POST['sign_smiles']) ? true : false);

  // Accesos Varios
  $admin->update_config('enable_register',isset($_POST['enable_register']) ? true : false);
  $admin->update_config('enable_search',isset($_POST['enable_search']) ? true : false);
  if($_POST['user_session_life'] !== Master::$config['user_session_life']) { $admin->update_config('user_session_life', (int) $_POST['user_session_life']); }
  if($_POST['user_connected_range'] !== Master::$config['user_connected_range']) { $admin->update_config('user_connected_range', (int) $_POST['user_connected_range']); }

  // Captcha
  //if($_POST['captcha_dir'] !== Master::$config['news_toshow']) { $admin->update_config('captcha_dir', $_POST['captcha_dir']); }
  //$admin->update_config('captcha_answer', isset($_POST['captcha_answer']) ? true : false);
  //$admin->update_config('captcha_login', isset($_POST['captcha_login']) ? true : false);
  //$admin->update_config('captcha_mp', isset($_POST['captcha_mp']) ? true : false);
  //$admin->update_config('captcha_newtopic', isset($_POST['captcha_newtopic']) ? true : false);
  //$admin->update_config('captcha_register', isset($_POST['captcha_register']) ? true : false);

  // Paginado
  if($_POST['pagelimit_answers'] !== Master::$config['pagelimit_answers']) { $admin->update_config('pagelimit_answers', (int)$_POST['pagelimit_answers']); }
  if($_POST['pagelimit_mps'] !== Master::$config['pagelimit_mps']) { $admin->update_config('pagelimit_mps', (int)$_POST['pagelimit_mps']); }
  if($_POST['pagelimit_topics'] !== Master::$config['pagelimit_topics']) { $admin->update_config('pagelimit_topics', (int)$_POST['pagelimit_topics']); }
  if($_POST['pagelimit_users'] !== Master::$config['pagelimit_users']) { $admin->update_config('pagelimit_users', (int)$_POST['pagelimit_users']); }
  if($_POST['pagelimit_nodes'] !== Master::$config['pagelimit_nodes']) { $admin->update_config('pagelimit_nodes', (int)$_POST['pagelimit_nodes']); }
  redirect('admin_panel');
  // if($_POST[''] !== Master::$config['']) { $admin->update_config('', $_POST['']); }
 }


// asignamos la data al rain
$view->add_key('template', 'panel');

// Dibujamos
$view->add_template('admin');