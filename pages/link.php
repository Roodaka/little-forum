<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');
if(isset($_GET['v']))
 {
  $refer = new Referencias($db);
  $refer->scan_out(trim($_GET['v']));
 }
else
 {
  redirect('home');
 }

