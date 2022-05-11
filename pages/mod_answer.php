<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');


// Si están seteados tanto el ID como la función se ejecuta, siempre y cuando
// también tenga el rango requerido para moderar.
if(isset($_GET['v']) && isset($_GET['f']) )
 {
  $aid = (int) $_GET['v'];

  $temas = new Temas($db);
  $answer = $temas->get_answer($aid);
  if($answer === false) { redirect('home'); }
  $topic = $temas->get_topic($answer['tema_id']);

  $foros = new Foros($db);
  $cond_foro = $foros->get_conditions($topic['foro_id']);

  $view->add_key('answer', $answer);
  $view->add_key('tdata', $topic);

  $view->add_key('mod', $_GET['f']);

  $mod = (Grades::is_level(Grades::GRADE_LMOD) === true && in_array(Master::$id, $cond_foro['mods'])) ? true : Grades::is_level(Grades::GRADE_GMOD);
  if($mod === true)
   {
    if($_SERVER['REQUEST_METHOD'] == 'POST')
     {
      switch($_GET['f'])
       {
        // Mostrar una respuesta
        case 'show':
         $res = $temas->show_answer($aid);
         break;

        // Ocultar una respuesta
        case 'hide':
         $res = $temas->hide_answer($aid);
         break;

        // Aprobar respuesta
        case 'approbe':
         $res = $temas->show_answer($aid);
         break;

        // Borrar una respuesta
        case 'delete':
         $res = $temas->delete_answer($aid);
         break;
       }
      if($res !== false) { $view->add_key('passed', 'true'); }
     }
    $view->add_template('ajax/moderate');
   } else { redirect('result', 'access'); }
 }