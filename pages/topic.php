<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');

if(isset($_GET['v'])) { $id = (int) $_GET['v']; }
else { redirect('index.php', 'home'); }

if(isset($_GET['p'])) { $page = (int) $_GET['p']; }
else { $page = 1; }

$foros = new Foros($db);
$temas = new Temas($db);

$data = $temas->get_topic($id);
$cond_foro = $foros->get_conditions($data['foro_id']);

// No existe este tema, redireccionamos.
if($data == false && $foros->can_enter($cond_foro) == false) { redirect('home'); }
else
 {
  // Una linea simple para definir si el usuario es moderador o no
  $mod = (Grades::is_level(Grades::GRADE_LMOD) === true && in_array(Master::$id, $cond_foro['mods'])) ? true : Grades::is_level(Grades::GRADE_GMOD);

  $view->add_key('mod', $mod);
  // Si somos usuarios comunes y el tema está borrado, llevamos a un 404
  if($data['estado'] !== Temas::TOPIC_STATUS_APPROBED && $mod == false && $data['user_id'] !== Master::$id) { redirect('result', '404'); }
  else
   {
    // Marcamos el tema como visto
    $temas->visit_topic($id);

    // Asignamos el título de la página
    $view->add_key('web_title', $data['titulo']);

    // Asignamos el arbol de origen del tema
    $view->add_key('source', $temas->get_source_tree($id));

    $view->add_key('tema', $data);
    // Si el tema tiene respuestas, procedemos a cargarlas.
    if($data['respuestas'] >= 1)
     {
      $respuestas = array();
      $r = 0;
      $answers = $temas->get_answers($data['id'], $page, Master::$config['pagelimit_topics'], $mod);
      while($resp = $answers->fetchrow())
       {
        $r = ++$r;
        $respuestas[$r] = $resp;
       }
      $view->add_key('resp', $respuestas);
     }
    $view->add_template('topic');
   }
 }