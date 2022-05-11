<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');
// zonas horarias
$timezones = array_keys(require('libs/data.timezones.php'));

// tipos de archivos
//$filetypes = array('image/png' => '.png', 'image/jpeg' => '.jpeg', 'image/jpg' => '.jpg', 'image/gif' => '.gif', 'image/ico' => '.ico');
$filetypes = array('png', 'jpg','jpeg','gif','ico');

$selectedtypes = array();
foreach(array_keys(json_decode(Master::$config['uploader_avatar_filetypes'], true)) as $key)
 {
  $selectedtypes[] = str_replace('image/', '', $key);
 }


// Tipos de envío de mails
$mailtypes = array('mail', 'smtp');

// Modos de avatar
$uploadmodes = array('none', 'file', 'url', 'gravatar');

// tipos de cache
$cache_types = array('none', 'file', 'mysql');
if(extension_loaded('Memcached')) { $cache_types[] = 'memcached'; }
if(extension_loaded('xcache')) { $cache_types[] = 'xcache'; }
if(function_exists('apc_add')) { $cache_types[] = 'apc'; }

if($_SERVER['REQUEST_METHOD'] == 'POST')
 {
  // Instanciamos Admin.
  $admin = new Admin($db);

  // Vamos actualizando las configuraciones y para no ejecutar consultas
  // innecesarias, validamos que los campos NO coincidan con los actuales

  // Opciones generales
  if($_POST['site_title'] !== Master::$config['site_title']) { $admin->update_config('site_title', htmlspecialchars($_POST['site_title'])); }
  if($_POST['site_desc'] !== Master::$config['site_desc']) { $admin->update_config('site_desc', htmlspecialchars($_POST['site_desc'])); }
  if($_POST['site_tags'] !== Master::$config['site_tags']) { $admin->update_config('site_tags', htmlspecialchars($_POST['site_tags'])); }
  if($_POST['site_patch'] !== Master::$config['site_patch']) { $admin->update_config('site_patch', $_POST['site_patch']); }
  if($_POST['site_timemode'] !== Master::$config['site_timemode']) { $admin->update_config('site_timemode', $_POST['site_timemode']); }

  // Modo mantenimiento
  if($_POST['maint_enable'] !== Master::$config['site_maintenance']) { $admin->update_config('site_maintenance', isset($_POST['maint_enable']) ? true : false); }
  if($_POST['maint_title'] !== Master::$config['maintenance_title']) { $admin->update_config('maintenance_title', $_POST['maint_title']); }
  if($_POST['maint_comment'] !== Master::$config['maintenance_commen']) { $admin->update_config('maintenance_comment', $_POST['maint_comment']); }

  // Cookies
  $admin->update_config('enable_cookies',isset($_POST['cookies']) ? true : false);
  if($_POST['cookies_life'] !== Master::$config['cookie_life']) { $admin->update_config('cookie_life', $_POST['cookies_life']); }
  if($_POST['cookies_name'] !== Master::$config['cookie_name']) { $admin->update_config('cookie_name', $_POST['cookies_name']); }
  if($_POST['cookies_path'] !== Master::$config['cookie_path']) { $admin->update_config('cookie_path', $_POST['cookies_path']); }

  // Cache
  if($_POST['cache_mode'] !== Master::$config['cache_mode']) { $admin->update_config('cache_mode', $_POST['cache_mode']); }
  if($_POST['cache_life'] !== Master::$config['cache_life']) { $admin->update_config('cache_life', $_POST['cache_life']); }

  // Vaciamos el caché
  if(isset($_POST['clear_cache'])) { Cache::clear(); }

  // Mail
  if($_POST['mail_mode'] !== Master::$config['mail_sendtype']) { $admin->update_config('mail_sendtype', $_POST['mail_mode']); }
  if($_POST['mail_user'] !== Master::$config['mail_user']) { $admin->update_config('mail_user', $_POST['mail_user']); }
  if($_POST['mail_pass'] !== Master::$config['mail_smtp_password']) { $admin->update_config('mail_smtp_password', $_POST['mail_pass']); }
  if($_POST['mail_port'] !== Master::$config['mail_smtp_port']) { $admin->update_config('mail_smtp_port', $_POST['mail_port']); }
  if($_POST['mail_server'] !== Master::$config['mail_smtp_server']) { $admin->update_config('mail_smtp_server', $_POST['mail_server']); }

  // Subida de archivos
  if($_POST['uploader_default'] !== Master::$config['uploader_avatar_default']) { $admin->update_config('uploader_avatar_default', $_POST['uploader_default']); }
  $allowed_filetypes = array();
  if(isset($_POST['uploader_types_png'])) { $allowed_filetypes['image/png'] = '.'.$_POST['uploader_types_png']; }
  if(isset($_POST['uploader_types_jpg'])) { $allowed_filetypes['image/jpg'] = '.'.$_POST['uploader_types_jpg']; }
  if(isset($_POST['uploader_types_jpeg'])) { $allowed_filetypes['image/jpeg'] = '.'.$_POST['uploader_types_jpeg']; }
  if(isset($_POST['uploader_types_gif'])) { $allowed_filetypes['image/gif'] = '.'.$_POST['uploader_types_gif']; }
  if(isset($_POST['uploader_types_ico'])) { $allowed_filetypes['image/ico'] = '.'.$_POST['uploader_types_ico']; }
  $admin->update_config('uploader_avatar_filetypes', json_encode($allowed_filetypes));

  if($_POST['uploader_mode'] !== Master::$config['uploader_avatar_mode']) { $admin->update_config('uploader_avatar_mode', $_POST['uploader_mode']); }
  if($_POST['uploader_height'] !== Master::$config['uploader_height']) { $admin->update_config('uploader_max_height', $_POST['uploader_height']); }
  if($_POST['uploader_size'] !== Master::$config['uploader_size']) { $admin->update_config('uploader_max_size', $_POST['uploader_size']); }
  if($_POST['uploader_width'] !== Master::$config['uploader_width']) { $admin->update_config('uploader_max_width', $_POST['uploader_width']); }


  redirect('admin_master');
  //$admin->update_config('', $_POST['']);
 }

$view->add_key('filetypes', $filetypes);
$view->add_key('selectedtypes', $selectedtypes);
$view->add_key('uploadtypes', $uploadmodes);
$view->add_key('sendtypes', $mailtypes);
$view->add_key('site_timezones', $timezones);
$view->add_key('cache_modes', $cache_types);
$view->add_key('cache_size', Cache::size());
// Dibujamos
$view->add_key('template', 'master');
$view->add_template('admin');