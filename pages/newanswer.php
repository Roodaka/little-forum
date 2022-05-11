<?php
// Si recibimos un post...
if($_SERVER['REQUEST_METHOD'] == 'POST')
 {
  // Chequeamos que estén nuestras variables
  if(!empty($_POST['content']) && isset($_GET['v']))
   {
    if(Master::$config['captcha_answer'] == true && $_POST['captcha'] !== $_SESSION['captcha']) { echo 'captcha'; }
    else
     {
      $temas = new Temas($db);
      $tema = $temas->get_topic((int) $_GET['v']);
      if($tema !== false)
       {
        if($tema['comentar'] == 1)
         {
          $res = $temas->new_answer($tema['id'], $_POST['content']);
          if($res == true)
           {
            redirect('topic', (int) $_GET['v']);
           } else { echo 'error: "false"'; }
         } else { echo 'error: "'.$lang['error_cannotanswer'].'"'; }
       } else { echo 'topic'; }
     }
   } else { echo '0: "Completa todos los campos!"'; }
 } else { echo '0'; }