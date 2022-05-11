<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');
// Forum.php
if(!isset($_GET['v'])) { redirect('home'); }
else
 {
  $id = (int) $_GET['v'];
  if(isset($_GET['p'])) { $page = (int) $_GET['p']; }
  else { $page = 1; }

  $foros = new Foros($db);
  $temas = new Temas($db);
  // Obtenemos los datos del foro
  $fdata = $foros->get_forum_data($id) or redirect('home');

  // no puede acceder a este foro.
  $can_enter = (Grades::is_level($fdata['nivel_ver']) == true) ? true : $foros->can_enter($fdata['condiciones']);

  if($can_enter === false) { redirect('result', 'access'); }
  else
   {
    // Marcamos el foro como leído si así se solicita
    if(isset($_GET['f']) && $_GET['f'] === 'mark' && Grades::is_level(Grades::GRADE_COMMON))
     {
      $foros->mark_forum_read($fdata['id']);
     }

    // Cargamos si el foro fue marcado como leído.
    $fdata['marktime'] = $foros->is_marked($fdata['id']);
    // Una linea simple para definir si el usuario es moderador o no
    $mod = (Grades::is_level(Grades::GRADE_LMOD) === true && in_array(Master::$id, $fdata['condiciones']['mods'])) ? true : Grades::is_level(Grades::GRADE_GMOD);

    // Agregamos el título a la web.
    $view->add_key('web_title', $fdata['nombre']);

    // Árbol de foros para el leftbar
    $view->add_key('lb_tree', $foros->get_forums_tree(false));

    // Datos del foro
    $view->add_key('foro', $fdata);

    // Subrforos
    $subforos = $foros->get_subforums($id);
    if($subforos !== false) { $view->add_key('subforos', $subforos); }
    else { $view->add_key('subforos', false); }

    // Fijados
    // solo se cargan en la primera pagina
    if($page == 1)
     {
      $sticks = $temas->get_sicks($id);
      if($sticks !== false)
       {
        $view->add_key('fijados', $sticks['list']);
        $total_fijados = (int)$sticks['cant'];
       }
      else { $view->add_key('fijados', false); }
     }
    else
     {
      $total_fijados = 0;
      $view->add_key('fijados', false);
     }
    // Temas comunes
    $topics = $temas->get_topics('t.fechahora DESC', $page, Master::$config['pagelimit_topics'], $id, $mod);
    if($topics !== false) { $view->add_key('temas', $topics['list']); }
    else { $view->add_key('temas', false); }

    $pager = new Paginator($fdata['temas'], Master::$config['pagelimit_topics'], Master::$config['pagelimit_nodes']);
    $view->add_key('paginado', $pager->paginate($page));

    $view->add_key('total_topics', ($total_fijados + $topics['cant']));


    // Liberamos algo de memoria.
    if(isset($topics)) { unset($topics); }
    if(isset($sticks)) { unset($sticks); }

    // Dibujamos la UI
    $view->add_template('leftbar');
    $view->add_template('forum');
   }
 }