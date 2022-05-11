<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');

class Mailer
 {
  // Instancia de la PHPMailer
  protected $mailer = null;

  // Configuraciones del envío de mails
  protected $sendtype = '';
  protected $user = null;
  protected $usertitle = '';



  public function __construct($sendtype = 'mail', $user = null, $usertitle = '', $host = null, $pass = null, $port = null)
   {
    $this->sendtype = $sendtype;
    $this->user = $user;
    $this->usertitle = $usertitle;

    if($this->sendtype == 'smtp')
     {
      // Cargamos la clase
      $this->mailer = new phpmailer();

      // Le indicamos a la clase que use SMTP
      $this->mailer->IsSMTP();

      // Sólo servidores con autenticación
      $this->mailer->SMTPAuth = true;

      // Puerto del servidor
      $this->mailer->Port = $port;

      // Host SMTP
      $this->mailer->Host       = $host;

      // Cuenta en el SMTP
      $this->mailer->Username   = $user;

      // Contraseña del servidor
      $this->mailer->Password   = $pass;
     }
   } // public function __construct();

  public function send($target, $title, $content)
   {
    if($this->sendtype == 'smtp')
     {
      $this->mailer->From = $this->user;
      $this->mailer->FromName = '';
      $this->mailer->AddAddress($target);
      $this->mailer->Subject = $title;
      $this->mailer->WordWrap = 80; // set word wrap
      $this->mailer->MsgHTML($content);
      $this->mailer->IsHTML(true); // send as HTML
      $this->mailer->Send();
     }
    else
     {
      $headers = 'From: '.$this->usertitle."\r\n".'Reply-To: '.$this->user;
      mail($target, $title, $content, $headers);
     }
   } // public function send
 } // class mailer();