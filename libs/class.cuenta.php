<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');

class Cuenta
 {
  /**
   * Instancia de LittleDB
   */
  protected $db = null;

  // Estados de cuenta
  const ACCOUNT_STATUS_BANNED = 0;  // Está baneado
  const ACCOUNT_STATUS_ACTIVE = 1;  // Activación de cuenta automática
  const ACCOUNT_STATUS_MAIL = 2;  // Requiere confirmación vía Mail
  const ACCOUNT_STATUS_ADM = 3;  // Requiere aprobación del admin

  /**
   * Constructor de la clase
   * @param object $db Instancia de LittleDB
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function __construct($db)
   {
    $this->db = $db;
   } // public function __construct();



  /**
   * Actualizar el perfil
   * @param string $nombre
   * @param int $nac_d Día de nacimiento
   * @param int $nac_m Mes de nacimiento
   * @param int $nac_y Año de nacimiento
   * @param int $sexo Sexo del usuario
   * @param string $titulo
   * @param string $firma
   * @param string $web Página Web del usuario
   * @param int $id ID del usuario objetivo
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function update_profile($nombre, $nac_d, $nac_m, $nac_y, $sexo, $firma, $web, $bio, $ubic, $id)
   {
    $parser = new Parser($this->db);
    $months = array('01' => 'january', '02' => 'february', '03' => 'march', '04' => 'april', '05' => 'may', '06' => 'june','07' => 'july','08' => 'august','09' => 'september','10' => 'october','11' => 'november', '12' => 'december');
    return $this->db->update('usuarios', array(
     'nombre' => htmlspecialchars($nombre),
     'fechanacimiento' => strtotime($nac_d.' '.$months[$nac_m].' '.$nac_y),
     'sexo' => (int)$sexo,
     'firma' => htmlspecialchars($firma),
     'firma_html' => ((Master::$config['sign_bbc'] == true) ? $parser->parse(htmlspecialchars($firma), (boolean) Master::$config['sign_images']) : htmlspecialchars($firma)),
     'web' => htmlspecialchars($web),
     'biografia' => htmlspecialchars($bio),
     'ubicacion' => htmlspecialchars($ubic)), array('id' => $id), false);
   } // public function update_profile();


  /**
   * Cambiar el Mail
   * @param string $mail Nueva dirección de correo
   * @param int $id ID del usuario objetivo
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function update_mail($mail, $id)
   {
    return $this->db->update('usuarios', array('mail' => $mail), array('id' => $id), false);
   } // public function update_mail();



  /**
   * Cambiar la contraseña
   * @param string $oldpass Contraseña anterior
   * @param string $newpass Nueva contraseña
   * @param int $id ID del usuario objetivo
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function update_pass($oldpass, $newpass, $id)
   {
    $enc = new PHPass(8, FALSE);
    $query = $this->db->query('SELECT '.$this->db->prefix.'contrasenia FROM usuarios WHERE id = ? LIMIT 1', array($id), true);
    if(!$enc->CheckPassword($oldpass, $query['contrasenia'])) { return false; }
    else
     {
      $this->db->update('usuarios', array('contrasenia' => $enc->HashPassword($newpass)), array('id' => $id), false);
      return true;
     }
   } // public function update_pass();



  /**
   * Obtener el avatar del usuario
   * @param int $id ID del usuario objetivo
   * @param string $patch URL del avatar
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
   public function update_avatar($id, $path = '')
     {
      if($path == 'gravatar')
       {
        // Sacamos el mail de la DB
        $query = $this->db->query('SELECT mail FROM '.$this->db->prefix.'usuarios WHERE id = ? LIMIT 1', (int) $id, true);
        if($query !== false)
         {
          // armamos la URL del avatar
          $url = 'http://www.gravatar.com/avatar/'.md5($query['mail']).'?s=120';
         }
       }
      else { $url = $path; }
      return $this->db->update('usuarios', array('avatar' => $url), array('id' => (int) $id), false);
     } // function update_avatar();



  /**
   * Chequear si existe el mail
   * @param string $mail Dirección de correo a comprobar
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function is_mail($mail)
   {
    $query = $this->db->query('SELECT id FROM '.$this->db->prefix.'usuarios WHERE mail = ? LIMIT 1', array(trim(strtolower($mail))), true);
    if(!$query || $query == false) { return false; }
    else { return true; }
   } // public function is_mail();



  /**
   * Chequear si existe el Usuario
   * @param string $nick Nick a comprobar
   * @return mixed id del nick o false
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function is_user($nick)
   {
    $query = $this->db->query('SELECT id FROM '.$this->db->prefix.'usuarios WHERE nick = ? LIMIT 1', array($nick), true);
    if(!$query || $query == false) { return false; }
    else { return $query['id']; }
   } // public function is_user();



  /**
   * Chequear si el hash de recuperación es válido
   * @param string $hash Hash a comprobar
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function is_validHash($hash)
   {
    $query = $this->db->query('SELECT id FROM '.$this->db->prefix.'recuperar WHERE hash = ? LIMIT 1', array(trim($hash)), true);
    $activate = $this->db->update('usuarios', array('estado' => self::ACCOUNT_STATUS_ACTIVE), array('id' => $query['id']), false);
    if(!$query || $query == false || $activate == false || !$query) { return false; }
    else { return true; }
   } // public function is_validHash



  /**
   * Iniciar el proceso de recuperación de contraseña (sin terminar)
   * @param string $mail Dirección de correo
   * @param string $nick Nick del usuario
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function lost_password($mail, $nick)
   {
    $query = $this->db->query('SELECT id FROM '.$this->db->prefix.'usuarios WHERE mail = ? && nick = ? LIMIT 1', array($mail, $nick), true);
    if(!$query || $query == false) { return false; }
     else
      {
       $hashq = $this->db->insert('recuperar', array('user_id' => $query['id'],'hash' => md5($nick.$mail),'fechahora' => time()), false);
       return true;
      }
   } // public function lostPasword();



  /**
   * Registrar un usuario
   * @param string $nick Nick de la cuenta
   * @param string $nombre Nombre del usuario
   * @param string $mail Dirección de Correo
   * @param string $pass Contraseña de la cuenta
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function new_user($nick, $nombre, $mail, $pass)
   {
    $enc = new PHPass(8, FALSE);
    // INSERTAMOS AL USUARIO NUEVO
    $ins = $this->db->insert('usuarios', array(
     'nick' => trim($nick),
     'nombre' => htmlspecialchars($nombre),
     'contrasenia' => $enc->HashPassword($pass),
     'mail' => trim(strtolower($mail)),
     'avatar' => 'filez/avatars/default.gif',
     'estado' => (int) Master::$config['user_need_activation'],
     'rango_id' => (int) Master::$config['user_registered_default_grade'],
     'mensajes' => 0,
     'ip_registro' => ip2long(getip()),
     'fecharegistro' => time(),
     'config_idioma' => Master::$data['config_idioma'],
     'config_tema' => Master::$data['config_tema'],
     'config_show_mail' => 0
     ), true);
    if($ins == false || !$ins) { exit($this->db->error());return false; }
    else
     {
      // Actualizo la cantidad de usuarios en el foro
      $this->db->query('UPDATE '.$this->db->prefix.'estadisticas SET usuarios = usuarios + 1, ultimousuario = ? WHERE  id = ? ', array($ins, '1'), false);
      if(Master::$config['user_need_activation'] === 'mail')
       {
        // Necesita la activación por mail.
        //$mail = new M
       }
      return true;
     }
   } // public function new_user();



  /**
   * Cargar la lista de usuarios.
   * @param int $page Número de la página actual
   * @param int $limit Cantidad de usuarios cargados por página
   * @param string $order Tipo de órden que tendrán
   * @return array Lista de usuarios
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function get_users($page, $limit, $order = 'u.fecharegistro')
   {
    $limits = paginate($page, $limit);
    $query = $this->db->query('SELECT u.id, u.nick, u.avatar, u.nombre, u.ultimafecha, u.mensajes, r.nombre AS rango, r.color, r.bold FROM '.$this->db->prefix.'usuarios AS u LEFT JOIN '.$this->db->prefix.'rangos AS r ON r.id = u.rango_id GROUP BY u.id ORDER BY '.$order.' LIMIT '.$limits[0].', '.$limits[1], null, false);

    // Inicializamos y armamos el arreglo con la lista
    $users = array();
    while($user = $query->fetchrow())
     {
      $users[] = $user;
     }
    return $users;
   } // public function get_users();



 /**
  * Obtener todos los datos necesarios para el perfil (stats, datos personales,
  *  datos de cuenta)
  * @param mixed $target ID del usuario objetivo
  * @return array Datos del usuario
  * @author Cody Roodaka <roodakazo@hotmail.com>
  */
  public function get_profile($target)
   {
    return $this->db->query('SELECT COUNT(DISTINCT(c.id)) AS comentarios, COUNT(DISTINCT(t.id)) AS temas, u.id, u.nick, u.nombre, u.mail, u.config_show_mail, u.avatar, u.estado, u.fechanacimiento, u.sexo, u.firma, u.firma_html, u.web, u.ip_registro, u.fecharegistro, u.ultimafecha, u.ultimaip, u.biografia, u.ubicacion, r.nombre AS rango, r.color, r.bold FROM '.$this->db->prefix.'usuarios AS u LEFT JOIN '.$this->db->prefix.'temas AS t ON u.id = t.user_id LEFT JOIN '.$this->db->prefix.'temas_respuestas AS c ON c.user_id = u.id LEFT JOIN '.$this->db->prefix.'rangos AS r ON r.id = u.rango_id WHERE u.id = ? OR u.nick = ? LIMIT 1', array((int) $target, strtolower($target)), true);
   } // public function get_profile();



  /**
   * Obtener la lista de usuarios Online
   * @param int $limit Cantidad máxima a cargar
   * @return array Lista de usuarios conectados
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function get_online_list($limit)
   {
    $query = $this->db->query('SELECT u.id, u.nick, r.nombre AS rango, r.color, r.bold FROM '.$this->db->prefix.'usuarios AS u LEFT JOIN '.$this->db->prefix.'rangos AS r ON r.id = u.rango_id WHERE u.ultimafecha > ?', (time() - Master::$config['user_connected_range']), false);
    if($query !== false && $query->numrows() > 0)
     {
      $list = array();
      while($user = $query->fetchrow())
       {
        $list[] = $user;
       }
      return array('list' => $list, 'cant' => $query->numrows());
     }
    else { return false; }
   } // public function get_online_list();



  /**
   * Banear a un usuario
   * @param int $uid ID del usuario objetivo
   * @param string $reason Razón por la cual se banea al usuario
   * @param int $time Duración de la sansión (si es cero, se desbanea)
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>, a base de Ignacio Daniel Rostagno <ignaciorostagno@vijona.com.ar>
   */
  public function ban_user($uid, $reason, $time)
   {
    $check = $this->db->query('SELECT id FROM sansiones WHERE user_id = ? LIMIT 1', $uid, true);
    if($check === false)
     {
      $this->db->insert('sansiones', array(
       'user_id' => (int) $uid,
       'moderador' => Master::$id,
       'razon' => htmlspecialchars($reason),
       'fechahora' => time(),
       'duracion' => (int) $time
       ), false);
      $this->db->update('usuarios', array('estado' => self::ACCOUNT_STATUS_BANNED), array('id', (int) $uid), false);
     }
    else
     {
      if($time === 0)
       {
        $this->db->update('usuarios', array('estado' => self::ACCOUNT_STATUS_ACTIVE), array('id', (int) $uid), false);
        return $this->db->delete('sansiones', array('user_id' => (int) $uid), false);
       }
      // Cambiamos la duración de la sansión
      else
       {
        $this->db->update('usuarios', array('estado' => self::ACCOUNT_STATUS_BANNED), array('id', (int) $uid), false);
        return $this->db->update('sansiones', array('duracion' => (int) $time), array('id', (int) $uid), false);
       }
     }
   } // public function ban_user();



  // Loguear un usuario (validación en el controlador)
  public function login($id, $pass, $cookies = false)
   {
    // obtenemos la contraseña del usuario
    $query = $this->db->query('SELECT id, contrasenia, estado FROM '.$this->db->prefix.'usuarios WHERE id = ? LIMIT 1', array($id), true);
    if($query['estado'] == self::ACCOUNT_STATUS_BANNED) { return 'ban'; }
    else
     {
      $enc = new PHPass(8, FALSE);
      if(!$enc->CheckPassword($pass, $query['contrasenia']))
       {
        return 'pass';
       }
      else
       {
        // fijamos los datos
        $nav = md5($_SERVER['HTTP_USER_AGENT']);
        $ip = ip2long(getip());
        $hash = md5($query['id']);
        $time = time();

        $check = $this->db->query('SELECT user_id FROM '.$this->db->prefix.'sesiones WHERE hash = ?', $hash, true);
        if(!$check || $check == false)
         {
          // Creamos la sesion en la db
          $this->db->insert('sesiones', array(
           'hash' => $hash,
           'user_id' => $query['id'],
           'nav' => $nav,
           'ip' => $ip,
           'datetime' => $time), false);
         }
        else
         {
          // actualizamos con los nuevos datos
          $this->db->update('sesiones', array(
           'user_id' => $query['id'],
           'nav' => $nav,
           'ip' => $ip,
           'datetime' => $time
           ), array('hash' => $hash), false);
         }
        // Seteamos los datos de sesion
        $_SESSION['nav'] = $nav;
        $_SESSION['ip'] = $ip;
        $_SESSION['id'] = $hash;
        $_SESSION['time'] = $time;

        if($cookies !== false)
         {
          $result = setcookie($cookies['name'], $hash, $cookies['duration'], $cookies['path'], $cookies['domain']);
          if($result !== false)
           {
            $_COOKIE[$cookies['name']] = $hash;
            $check = $this->db->query('SELECT user_id FROM '.$this->db->prefix.'cookies WHERE hash = ?', $hash, true);
            if(!$check || $check == false)
             {
              // Creamos la sesion en la db
              $this->db->insert('cookies', array(
              'hash' => $hash,
              'user_id' => $query['id'],
              'nav' => $nav,
              'ip' => $ip,
              'datetime' => $time), false);
             }
            else
             {
              // actualizamos con los nuevos datos
              $this->db->update('cookies', array(
               'user_id' => $query['id'],
               'nav' => $nav,
               'ip' => $ip,
               'datetime' => $time
               ), array('hash' => $hash), false);
             }
           }
         }
        $this->db->update('usuarios', array('ultimaip' => $ip, 'ultimafecha' => time()), array('id' => $query['id']),false);
        return true;
       }
     }
   } //  public function logIn();



  /**
   * Desloguearse
   * @param boolean $cookies Habilitar o no las cookies
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function logout()
   {
    $this->db->delete('sesiones', array('user_id' => Master::$id), false);
    if(isset($_COOKIE[Master::$config['cookie_name']]) && Master::$config['enable_cookies'] == true)
     {
      setcookie(Master::$config['cookie_name'], $_SESSION['id'], (time() - Master::$config['cookie_life']), Master::$config['cookie_path'], Master::$config['site_host']);
      $this->db->delete('cookies', array('user_id' => Master::$id), false);
      unset($_COOKIE);
     }
    unset($_SESSION);
    session_destroy();
   } // public function logout();
 } // class Cuenta