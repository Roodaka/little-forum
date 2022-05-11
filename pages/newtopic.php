<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');
if(!isset($_GET['v'])) { redirect('home'); }
else
 {
  $temas = new Temas($db);
  $foros = new Foros($db);
  if(!isset($_POST['enviar']))
   {
    $view->add_key('types', $temas->get_types());
    $view->add_key('forum', $foros->get_forum_data($_GET['v']));
    $view->add_template('newtopic', false);
   }
  else
   {
    if(isset($_POST['titulo']) && $_POST['titulo'] !== '')
     {
      if(isset($_POST['content']) && $_POST['content'] !== '')
       {
        // Armamos el arreglo de opciones del tema
        $array = array(
         'fijar' => (isset($_POST['fijar']) && Grades::is_level(Grades::GRADE_LMOD) == true) ? 1 : 0,  // Fijar el tema
         'comentar' => (isset($_POST['nocomentar']) && Grades::is_level(Grades::GRADE_LMOD) == true) ? 0 : 1,  // Habilitar o no los comentarios
         'firmas' => (isset($_POST['nofirmas'])) ? 0 : 1,  // Firmas
         'prefijos' => (isset($_POST['noprefix']) || $_POST['prefijos'] == 'null') ? 0 : 1);  // ponemos o no los prefijos
        // Creamos el tema
        $create = $temas->new_topic($_GET['v'], $_POST['prefijos'], $_POST['titulo'], $_POST['content'], $array);
        if(!$create || $create == false) { $view->add_key('error', 'general'); }
        else
         {
          // Redirijimos al tema creado
          redirect('topic', (int) $create);
         }
       }
     }
   }
 }