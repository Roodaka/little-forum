<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');
$cuenta = new Cuenta($db);
if(isset($_POST['ingresar']))
 {
  if(!empty($_POST['user']) && !empty($_POST['pass']))
   {
    $userid = $cuenta->is_user(trim($_POST['user']));
    if($userid != false)
     {
      if(isset($_POST['setter']) && Master::$config['enable_cookies'])
       {
        $cookies = array(
         'name' => Master::$config['cookie_name'],
         'domain' => Master::$config['site_host'],
         'duration' => (time() + Master::$config['cookie_life']),
         'path' => Master::$config['cookie_path']
         );
       }
      else { $cookies = false; }

      $res = $cuenta->login($userid, trim($_POST['pass']), $cookies);
      // Logueó, lo redireccionamos
      if($res === true)
       {
        // Redireccionamos a la home
        if(isset($_GET['f']) && isset($_GET['v']))
         {
          redirect($_GET['f'], $_GET['v']);
         } else { redirect('home'); }
       }
      elseif($res == 'pass') { $view->add_key('error', 'password'); }
      elseif($res == 'ban') { $view->add_key('error', 'banned'); }
     } else { $view->add_key('error', 'notaccount'); }
   } else { $view->add_key('error', 'empty'); }
 }
elseif(isset($_GET['s']) && $_GET['s'] == 'success')
 {
  $view->add_key('success', '1');
 }
elseif(isset($_GET['f']) && $_GET['f'] == 'exit')
 {
  $cuenta->logout();
  // Redireccionamos a la home
  redirect('home');
 }
// Agregamos el título a la web
$view->add_key('web_title', $lang['login_send']);
// Dibujamos el login de todas formas
$view->add_template('login', false);