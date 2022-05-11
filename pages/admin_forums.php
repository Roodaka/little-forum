<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');
// creamos la instancia de la clase admin
$foros = new Foros($db);
$cats = new Categorias($db);
$types = array( array('key' => 'common', 'num' => Foros::FORUM_COMMON), array('key' => 'redir', 'num' => Foros::FORUM_REDIR), array('key' => 'locked', 'num' => Foros::FORUM_LOCKED));
if(!isset($_GET['f']))
 {
  $view->add_key('forums', $foros->get_forums_tree(false));
  $view->add_key('template', 'forums');
 }
elseif($_GET['f'] === 'edit_forum' && isset($_GET['v']))
 {
  // Datos del foro
  $view->add_key('forum_data', $foros->get_forum_data((int) $_GET['v']));

  // Lista de categorías
  $view->add_key('categories', $cats->get_categories());

  // Lista de foros
  $view->add_key('forums', $foros->get_forums_tree(false));

  // Asignamos los tipos de foros disponibles
  $view->add_key('types', $types);

  // Asignamos los niveles de acceso
  $view->add_key('grades', $grades->get_grades(Grades::TYPE_ASSIGN));
  $view->add_key('template', 'forum_edit');

  if($_SERVER['REQUEST_METHOD'] === 'POST')
   {
    if(isset($_POST['delete']))
     {
      $res = $foros->delete_forum($_GET['v']);
     }
    $res = $foros->update_forum_data($_GET['v'], $_POST['name'], $_POST['description'], $_POST['category'], $_POST['father'], $_POST['type'], $_POST['redir'], $_POST['hits'], $_POST['nivel_ver'], $_POST['nivel_crear']);
    if($res !== false)
     {
      // Borramos el caché
      Cache::delete('forums_tree_leftbar'); //  Árbol lateral
      Cache::delete('forums_tree_home');  // Portada
      Cache::delete('forum_'.$_GET['v'].'_data');
      redirect('admin_forums');
     }
   }
 }
elseif($_GET['f'] === 'new_forum')
 {
  $view->add_key('template', 'forum_new');
  // Lista de categorías
  $view->add_key('categories', $cats->get_categories());
  // Seteamos la categoría y/o el padre donde que queremos agregar el foro
  if(isset($_GET['v'])) { $view->add_key('to_category', (int) $_GET['v']); }
  $view->add_key('to_forum', ((isset($_GET['p'])) ? (int) $_GET['p'] : 0));
  // Asignamos los tipos de foros disponibles
  $view->add_key('types', $types);
  // Lista de foros
  $view->add_key('forums', $foros->get_forums_tree(false));
  // Asignamos los niveles de acceso
  $view->add_key('grades', $grades->get_grades(Grades::TYPE_ASSIGN));

  // Asignamos los tipos de foros disponibles
  $view->add_key('types', array( array('key' => 'common', 'num' => Foros::FORUM_COMMON), array('key' => 'redir', 'num' => Foros::FORUM_REDIR), array('key' => 'locked', 'num' => Foros::FORUM_LOCKED)));

  if($_SERVER['REQUEST_METHOD'] === 'POST')
   {
    $res = $foros->new_forum($_POST['name'], $_POST['description'], $_POST['category'], $_POST['father'], $_POST['type'], $_POST['redir'], $_POST['hits'], $_POST['nivel_ver'], $_POST['nivel_crear']);
    if($res !== false)
     {
      // Borramos el caché
      Cache::delete('forums_tree_leftbar'); //  Árbol lateral
      Cache::delete('forums_tree_home');  // Portada
      redirect('admin_forums');
     }
   }
 }

// Categorías
elseif($_GET['f'] === 'edit_category' && isset($_GET['v']))
 {
  $cat_data = $cats->get_category((int) $_GET['v']);
  // Datos de la categoría a editar
  $view->add_key('cat_data', $cat_data);

  // Lista de categorías
  $view->add_key('categories', $cats->get_categories());
  // Lista de niveles de acceso
  $view->add_key('grades', $grades->get_grades(Grades::TYPE_ASSIGN));

  $view->add_key('template', 'category_edit');

  if($_SERVER['REQUEST_METHOD'] === 'POST')
   {
    if((int) $_POST['category'] === (int) $cat_data['orden']) { $order = false; }
    else { $order = ((int) $_POST['category'] +1); }
    $res = $cats->update_category($_GET['v'], $_POST['name'], $_POST['level'], $order);
    if($res !== false)
     {
      // Borramos el caché
      Cache::delete('forums_tree_leftbar'); //  Árbol lateral
      Cache::delete('forums_tree_home');  // Portada
      redirect('admin_forums');
     }
   }
 }
elseif($_GET['f'] === 'new_category')
 {
  // Lista de categorías
  $view->add_key('categories', $cats->get_categories());
  // Lista de niveles de acceso
  $view->add_key('grades', $grades->get_grades(Grades::TYPE_ASSIGN));
  $view->add_key('template', 'category_new');

  if($_SERVER['REQUEST_METHOD'] === 'POST')
   {
    $res = $cats->new_category($_POST['name'], $_POST['level'], ($_POST['category'] + 1));
    if($res !== false)
     {
      // Borramos el caché
      Cache::delete('forums_tree_leftbar'); //  Árbol lateral
      Cache::delete('forums_tree_home');  // Portada
      redirect('admin_forums');
     }
   }
 }

// Dibujamos
$view->add_template('admin');