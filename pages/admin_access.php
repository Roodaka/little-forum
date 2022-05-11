<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');

if(!isset($_GET['f']))
 {
  // Listamos los Rangos disponibles para ver o modificar
  $view->add_key('grades_pro', $grades->get_grades(Grades::TYPE_ASSIGN));
  $view->add_key('grades_com', $grades->get_grades(Grades::TYPE_AUTO));
  $view->add_key('template', 'access');
 }
elseif($_GET['f'] === 'new')
 {
  if($_SERVER['REQUEST_METHOD'] === 'POST')
   {
    $bold = (isset($_POST['bold'])) ? true : false;
    $create = $grades->create((int) $_POST['type'], (int) $_POST['cant'], $_POST['name'], $_POST['color'], $bold, $_POST['icon']);
    if($create === false) { $view->add_key('error', 'failed');}
    else { redirect('admin_access'); }

   }
  // Creamos un nuevo Rango
  $view->add_key('template', 'grade_new');
 }
elseif($_GET['f'] === 'edit' && isset($_GET['v']))
 {
  $g_data = $grades->get_grade_data((int)$_GET['v']);
  $view->add_key('g_data', $g_data);
  // Definimos un arreglo con los tipos de rangos
  $g_types = array(Grades::TYPE_AUTO, Grades::TYPE_ASSIGN);

  // Definimos un arreglo con los permisos disponibles
  $g_actions = array(
   array('key' => 'user_see_home', 'value' => Grades::USER_SEE_HOME),
   array('key' => 'user_login', 'value' => Grades::USER_LOGIN),
   array('key' => 'user_no_need_aprobation', 'value' => Grades::USER_NO_NEED_APROBATION),
   array('key' => 'user_edit_signature', 'value' => Grades::USER_EDIT_SIGNATURE),
   array('key' => 'user_edit_avatar', 'value' => Grades::USER_EDIT_AVATAR),
   array('key' => 'user_edit_users', 'value' => Grades::USER_EDIT_USERS),
   array('key' => 'user_ban', 'value' => Grades::USER_BAN),
   array('key' => 'user_see_ip', 'value' => Grades::USER_SEE_IP),
   array('key' => 'user_delete_users', 'value' => Grades::USER_DELETE_USERS),
   array('key' => 'user_hide_on_list', 'value' => Grades::USER_HIDE_ON_LIST),
   array('key' => 'user_vote', 'value' => Grades::USER_VOTE),
   array('key' => 'user_see_online_list', 'value' => Grades::USER_SEE_ONLINELIST),

   array('key' => 'content_create', 'value' => Grades::CONTENT_CREATE),
   array('key' => 'content_own_edit', 'value' => Grades::CONTENT_OWN_EDIT),
   array('key' => 'content_own_delete', 'value' => Grades::CONTENT_OWN_DELETE),
   array('key' => 'content_see_hidden', 'value' => Grades::CONTENT_SEE_HIDDEN),
   array('key' => 'content_vote', 'value' => Grades::CONTENT_VOTE),
   array('key' => 'content_aprobe', 'value' => Grades::CONTENT_APROBE),
   array('key' => 'content_all_edit', 'value' => Grades::CONTENT_ALL_EDIT),
   array('key' => 'content_all_hide', 'value' => Grades::CONTENT_ALL_HIDE),
   array('key' => 'content_all_delete', 'value' => Grades::CONTENT_ALL_DELETE),

   array('key' => 'moderation_jump_limits', 'value' => Grades::MODERATION_JUMP_LIMITS),
   array('key' => 'moderation_manage_advertences', 'value' => Grades::MODERATION_MANAGE_ADVERTENCES),
   array('key' => 'server_manage', 'value' => Grades::SERVER_MANAGE),
   array('key' => 'server_server_master', 'value' => Grades::SERVER_MASTER),
   array('key' => 'server_maintenance', 'value' => Grades::SERVER_MAINTENANCE),
   array('key' => 'server_see_logs', 'value' => Grades::SERVER_SEE_LOGS),
   array('key' => 'server_see_stats', 'value' => Grades::SERVER_SEE_STATS));

  if($_SERVER['REQUEST_METHOD'] == 'POST')
   {
    var_dump($_POST);
    foreach($g_actions as $action)
    {
     if(isset($_POST[$action['key']]))
      {
       if($_POST[$action['key']] == 'yes' && !in_array((int)$action['value'], $g_data['permisos']))
        {
         echo 'on';
         $grades->add_access($_GET['v'], $action['value']);
        }
       elseif($_POST[$action['key']] == 'no' && in_array((int)$action['value'], $g_data['permisos']))
        {
         $grades->remove_access($action['value'], $g_data['permisos']);
        }
      }
    }
    redirect('admin_access');
   }
  // Editamos el Rango y/o le agregamos accesos
  $view->add_key('types', $g_types);
  $view->add_key('actions', $g_actions);
  $view->add_key('template', 'grade_edit');
 }

$view->add_template('admin');