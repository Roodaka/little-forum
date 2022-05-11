<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');

// Si están seteados tanto el ID como la función se ejecuta, siempre y cuando
// también tenga el rango requerido para moderar.
if(isset($_GET['v']) && isset($_GET['f']) )
 {
  $tid = (int) $_GET['v'];
  $temas = new Temas($db);
  $foros = new Foros($db);

  $view->add_key('topic', $temas->get_topic($tid));
  if($_GET['f'] == 'movetopic') { $view->add_key('forums', $foros->get_forums_tree(false)); }

  // Chequeamos que el tema exista.
  $topic = $temas->get_forum_of($tid);
  if($topic !== false)
   {
    $view->add_key('mod', $_GET['f']);

    // Obtenemos los datos del foro.
    $foro = $foros->get_forum_data($topic, false);
    $cond_foro = $foros->get_conditions($topic);

    $mod = (Grades::is_level(Grades::GRADE_LMOD) === true && in_array(Master::$id, $cond_foro['mods'])) ? true : Grades::is_level(Grades::GRADE_GMOD);

    // Fijat un tema
    if($_GET['f'] == 'stick' && $mod == true && isset($_POST['send'])) { $temas->stick($tid); $view->add_key('passed', true); }

    // Desfijar un tema
    elseif($_GET['f'] == 'unstick' && $mod == true && isset($_POST['send'])) { $temas->un_stick($tid); $view->add_key('passed', true); }

    // Habilitar comentarios
    elseif($_GET['f'] == 'enablecomments' && $mod == true && isset($_POST['send'])) { $temas->enable_coments($tid); $view->add_key('passed', true); }

    // Deshabilitar comentarios
    elseif($_GET['f'] == 'disablecomments' && $mod == true && isset($_POST['send'])) { $temas->disable_coments($tid); $view->add_key('passed', true); }

    // Agregar encuesta
    //elseif($_GET['f'] == 'addpoll' && && $mod == true) { }

    // Mover el tema a otro foro
    elseif($_GET['f'] == 'movetopic' && $mod == true && isset($_POST['target']) && isset($_POST['send'])) { $temas->move_topic($tid, (int) $_POST['target']); $view->add_key('passed', true); }

    // Borrar el tema
    elseif($_GET['f'] == 'delete' && $mod == true && isset($_POST['send'])) { $temas->delete_topic($tid); $view->add_key('passed', true); }

    // pase lo que pase, borramos el cache xD
    if(isset($_POST['send']))
     {
      Cache::delete('forum_'.$topic.'_sticks');
      Cache::delete('forum_'.$topic.'_data');

     }
   }
  $view->add_template('ajax/moderate', false);
 }