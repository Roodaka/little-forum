<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');
/**
 * libs/functions.php
 * Cody Roodaka 2011
 * Creado el 03/04/2011 01:17 a.m.
 */

/**
 * Generar un Código al azar
 * @param int $size Cantidad de caracteres del código a generar
 * @return string Código
 * @author Cody Roodaka <roodakazo@hotmail.com>
 */
function gencode($size)
 {
  $letras = 'ABCDEFGHIJKLMNOPRSTUVWXYZ1234567890abcdefghkmnprstwxz';
  $i = 0;
  $code = '';
   while($i < $size)
   {
    ++$i;
    $code .= $letras[mt_rand(0,52)];
   }
  return $code;
 } // function gencode();



/**
 * Cortar un texto si es necesario
 * @param string $text Texto a cortar
 * @param int $max Cantidad máxima de caracteres
 * @author Cody Roodaka <roodakazo@hotmail.com>
 */
function resizetext($text, $max = 100)
 {
  return (isset($text[$max])) ? substr($text, 0, $max).'&hellip;' : $text;
 } // function resizetext();



/**
 * Devuelve el valor redondeado para mejor entendimiento (?
 * @param int $size Tamaño del archivo
 * @return string Tamaño redondeado
 * @author Cody Roodaka <roodakazo@hotmail.com>
 */
function roundsize($size)
 {
  $ext = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
  $i = 0;
  while(($size/1024)>1)
   {
    $size=$size/1024;
    $i++;
   }
  return (round($size, 2).' '.$ext[$i]);
 } // function roundsize();


/**
 * Enviamos una cabecera con la redirección a otra URL
 * @param string $mod Módulo
 * @param string $value Valor
 * @param string $section Submódulo
 * @author Cody Roodaka <roodakazo@hotmail.com>
 */
function redirect($mod , $value = null, $section = null)
 {
  if($mod != null)
   {
    header('Location: index.php?a='.$mod.(($value !== null) ? '&v='.$value : '').(($section !== null) ? '&f='.$section : ''));
   }
  else { return false; }
 } // function redirect();



/**   594.32
 * Armamos una URL
 * @param string $mod Módulo objetivo
 * @param string $val Valor
 * @param string $sec Submódulo
 * @param int $page Número de página
 * @param string $title Título (para SEO)
 * @author Cody Roodaka <roodakazo@hotmail.com>
 */
function url($mod, $val = null, $sec = null, $page = null, $title = null)
 {
  return 'index.php?a='.$mod
  .(($val !== null) ? '&v='.$val : '')
  .(($title !== null) ? '-'.$title : '')
  .(($sec !== null) ? '&f='.$sec : '')
  .(($page !== null) ? '&p='.$page : '');
 } // function url();



/**
 * Función para la auto carga de clases
 * @param string $class Nombre de la clase
 * @author Cody Roodaka <roodakazo@hotmail.com>
 */
function autoload($class)
 {
  $target = LFS_ROOT.'libs/class.'.strtolower($class).'.php';
  if(file_exists($target)) { require($target); }
  else { exit('Error Fatal: No existe la clase :'.$target); }
 } // function autoload();



/**
 * Calcular el paginado para las consultas MySQL
 * @param int $page Número de página
 * @param int $limit Límite de resultados por página
 * @return array
 * @author Cody Roodaka <roodakazo@hotmail.com>
 */
function paginate($page, $limit)
 {
  return array((($page - 1) * $limit), ($page * $limit));
 } // function paginate();



/**
 * Obtener la ip del usuario
 * @return string IP del usuario
 * @author Cody Roodaka <roodakazo@hotmail.com>
 */
function getip()
 {
  if(isset($_SERVER))
   {
    if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) { return $_SERVER['HTTP_X_FORWARDED_FOR']; }
    elseif(isset($_SERVER['HTTP_CLIENT_IP'])) { return $_SERVER['HTTP_CLIENT_IP']; }
    else { return $_SERVER['REMOTE_ADDR']; }
   }
  else
   {
    if(getenv('HTTP_X_FORWARDED_FOR')) { return getenv('HTTP_X_FORWARDED_FOR'); }
    elseif(getenv('HTTP_CLIENT_IP')) { return getenv('HTTP_CLIENT_IP'); }
    else { return getenv('REMOTE_ADDR'); }
   }
 } // function getip();



/**}
 * Parseamos b, color y s si es necesario
 * @param string $text Texto a parsear
 * @param string $color Color Hexadecimal.
 * @param boolean $bold Negrita
 * @param boolean $italic Cursiva
 * @return string Texto parseado
 * @author Cody Roodaka <roodakazo@hotmail.com>
 */
function bbcsimple($text, $color = null, $bold = null, $italic = null)
 {
  if($italic === true) { $i = 'font-style: italic;'; } else { $i = ''; }
  if($bold === true) { $b = 'font-weight: bold;'; }  else { $b = ''; }
  if($color != null) { $c = 'color: #'.$color.';'; }  else { $c = ''; }
  return '<span style="'.$i.$b.$c.'">'.$text.'</span>';
 } // function bbcsimple();