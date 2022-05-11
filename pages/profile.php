<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');
/**
 * Perfil de por Alexander1712 para LittleForum Script
 */


if(!isset($_GET['v'])) { $target = Master::$id; }
else { $target = $_GET['v']; }

$cuenta = new Cuenta($db, $target);

// Estamos realizando una acción sobre la cuenta y sólo se podrá si es el dueño
// o un Moderador Global
if(!isset($_GET['f']))
 {
  $data = $cuenta->get_profile($target);
  if($data !== false && $data['id'] !== null)
   {
    // Agregamos el título a la web.
    $view->add_key('web_title', $lang['profile_web_title'].$data['nombre']);
    // traemos datos de la cuenta (datos personales, y estadisticas)
    $view->add_key('profile', $data);
    $view->add_template('leftbar');
    $view->add_template('profile');
   }
  else { redirect('home'); }
 }
elseif(isset($_GET['f']) && ((Grades::is_level(Grades::GRADE_GMOD) && Grades::is_access(Grades::USER_EDIT_USERS)) || ($target == Master::$id && Grades::is_access(Grades::USER_EDIT_AVATAR))))
 {
  // definimos el target para los botones
  $profile_data = $cuenta->get_profile($target);
  $view->add_key('target',$target);
  $view->add_key('profile', $profile_data);
  $view->add_key('edit', $_GET['f']);

// DATOS COMUNES ===============================================================
  if($_GET['f'] == 'edit_data')
   {
    $view->add_key('born_date', array('day' => date('d', $profile_data['fechanacimiento']),'month' => date('m', $profile_data['fechanacimiento']),'year' => date('Y', $profile_data['fechanacimiento'])));
    $view->add_key('dates', array('days' => array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'), 'months' => array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'), 'years' => array('1912', '1913', '1914', '1915', '1916', '1917', '1918', '1919', '1920', '1921', '1922', '1923', '1924', '1925', '1926', '1927', '1928', '1929', '1930', '1931', '1932', '1933', '1934', '1935', '1936', '1937', '1938', '1939', '1940', '1941', '1942', '1943', '1944', '1945', '1946', '1947', '1948', '1949', '1950', '1951', '1952', '1953', '1954', '1955', '1956', '1957', '1958', '1959', '1960', '1961', '1962', '1963', '1964', '1965', '1966', '1967', '1968', '1969', '1970', '1971', '1972', '1973', '1974', '1975', '1976', '1977', '1978', '1979', '1980', '1981', '1982', '1983', '1984', '1985', '1986', '1987', '1988', '1989', '1990', '1991', '1992', '1993', '1994', '1995', '1996', '1997', '1998', '1999', '2000', '2001', '2002', '2003', '2004', '2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012')));

    if($_SERVER['REQUEST_METHOD'] == 'POST')
     {
      $res = $cuenta->update_profile($_POST['nombre'], (int) $_POST['birth_d'], $_POST['birth_m'], (int) $_POST['birth_y'], (int) $_POST['sex'], $_POST['firma'], $_POST['web'], $_POST['bio'], $_POST['ubi'], $target);
      if($res == true)
       {
        $view->add_key('res', 'sucess');
        $view->add_key('msg', 'data');
       }
      else { $view->add_key('error', 'data_update'); }
     }
   }

// AVATAR ======================================================================
  elseif($_GET['f'] == 'edit_avatar' && Master::$config['uploader_avatar_mode'] !== 'none')
   {
    if(Master::$config['uploader_avatar_mode'] == 'gravatar') { $view->add_key('gravatar', 'http://www.gravatar.com/avatar/'.md5($profile_data['mail']).'s=120'); }

    if($_SERVER['REQUEST_METHOD'] == 'POST')
     {
      $uploader = new Uploader('files/avatars/', $profile_data['id'], Master::$config['uploader_max_size'], Master::$config['uploader_max_width'], Master::$config['uploader_max_height'], json_decode(Master::$config['uploader_avatar_filetypes'], true), Master::$config['uploader_avatar_default']);
      if(Master::$config['uploader_avatar_mode'] == 'file' && isset($_FILES))
       {
        $upload = $uploader->upload_file($_FILES['upload']);
       }

      // Subimos una url
      elseif(Master::$config['uploader_avatar_mode'] == 'url' && isset($_POST['url']))
       {
        $upload = $uploader->use_url(htmlspecialchars($_POST['url']));
       }

      // Mandamos a Gravatar
      elseif(Master::$config['uploader_avatar_mode'] == 'gravatar')
       {
        $upload = $uploader->use_gravatar($profile_data['mail']);
       }
      else
       {
        // Usamos el avatar por defecto
        $upload = $uploader->get_default();
       }
      if($upload !== true) { $view->add_key('error', 'upload_'.$uploader->error); }
      else
       {
        //
        $cuenta->update_avatar($target, $uploader->result);
        $view->add_key('success', 'true');
       }
     }
   }

// EDICIÓN DE CORREOS ==========================================================
  elseif($_GET['f'] == 'edit_mail')
   {
    if(isset($_POST['mail']) && empty($_POST['mail']))
     {
      if($cuenta->update_mail($_POST['mail'], $target) == true) { $view->add_key('save', true); }
      else { $view->add_key('error', 'mail'); $view->add_key('save', false); }
     }
    else
     {
      $view->add_key('save', false);
     }
   }



// CONTRASEÑA ==================================================================
  elseif($_GET['f'] == 'edit_pass')
   {
    if(isset($_POST['pass_b']) && isset($_POST['pass_c']) && isset($_POST['pass_a']) && !empty($_POST['pass_a']) && $_POST['pass_b'] == $_POST['pass_c'])
     {
      if($cuenta->update_pass($_POST['pass_a'], $_POST['pass_b'], $target) == true)
       {
        //mostrar mensaje de guardado con exito
        $view->add_key('save', true);
       } else { $view->add_key('error', 'password'); }
     }
   }

// Sanción =====================================================================
  elseif($_GET['f'] == 'ban' && Grades::is_access(Grades::USER_BAN))
   {
    if(isset($_POST['reason']) && isset($_POST['time']))
     {
      $res = $cuenta->ban_user($target, $_POST['reason'], $_POST['time']);
     }
   }

// Borrar Usuario ==============================================================
  elseif($_GET['f'] == 'delete')
   {

   }


  // Por último, mostramos la plantilla
  $view->add_template('leftbar');
  $view->add_template('editprofile');
 }
