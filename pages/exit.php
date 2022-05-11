<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');
// Borramos los datos de sesión
$cuenta = new Cuenta($db);
$cuenta->logout();

// Redireccionamos a la home
redirect('home');