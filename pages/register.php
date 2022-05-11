<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');
// Asignamos el título a la web.
$view->add_key('web_title', $lang['head_usermenu_register']);
/**
 * ESTRUCTURA DEL REGISTRO
 * PASO 1: Aceptar el reglamento
 * PASO 2: Ingresar los datos Primarios
 * PASO 3: Cuenta creada, pero inactiva
 * PASO 4: Activar cuenta
 * PASO 5: Recuperar Contraseña
 * PASO 6: Cambiar contraseña
 * VARIABLE QUE DEFINE LOS PASOS =  $_GET['p']
*/

if(!isset($_GET['p'])) { $step = 1; }
else { $step = (int) $_GET['p']; }

$cuenta = new Cuenta($db);
// Acuerdo
if($step == 1)
 {
  // Se registra
  if(isset($_POST['send']))
   {
    // Nos fijamos que no exista el mail
    if($cuenta->is_mail(trim($_POST['mail2'])) == true || $_POST['mail1'] != $_POST['mail2']) { $view->add_key('error', $lang['register_error_mail']); }
    else
     {
      // Chequeamos POR LAS DUDAS el usuario
      if($cuenta->is_user($_POST['username']) !== false) { $view->add_key('error', $lang['register_error_user']); }
      else
       {
        // Chequeamos que las contraseñas estén bien
        if($_POST['pass1'] !== $_POST['pass2']) { $view->add_key('error', 'pass'); }
        else
         {
          $result = $cuenta->new_user($_POST['username'], $_POST['realname'], $_POST['mail2'], $_POST['pass2']);
          if($result == true)
           {
            redirect('register&p=3');
           }
          else
           {
            $view->add_key('error', 'general');
           }
         }
       }
     }
   }
  else
   {
    $view->add_template('register', false);
   }
 }

// mensaje de cuenta creada
elseif($step == 2)
 {
  $view->add_key('title', $lang['register_activ_title']);
  // La cuenta ya está activada.
  if(Master::$config['user_need_activation'] === Cuenta::ACCOUNT_STATUS_ACTIVE) { redirect('login', null, 'success'); }

  // Activación por mail.
  elseif(Master::$config['user_need_activation'] === Cuenta::ACCOUNT_STATUS_MAIL) { $view->add_key('message', $lang['register_activ_mail']); }

  // Activación del personal administrativo.
  elseif(Master::$config['user_need_activation'] == Cuenta::ACCOUNT_STATUS_ADM) { $view->add_key('message', $lang['register_activ_admin']); }
  $view->add_template('register_message');
 }

// Activar la cuenta
elseif($step == 3)
 {
  $view->add_key('title', $lang['register_activ_title']);
  if($cuenta->is_validHash($_GET['v']) == false)
   {
    $view->add_key('message', $lang['register_activ_error']);
   }
  else
   {
    $view->add_key('message', $lang['register_activ_activ']);
   }
  $view->add_template('register_message');
 }