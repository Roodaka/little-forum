<?php
/**
 * Clase para manejar las actualizaciones del sistema
 * @author Cody Roodaka <roodakazo@hotmail.com> && Alexander Eberle <alexander1712>
 */
class External
 {
  const LAST_VERSION = 1;
  const OLD_VERSION = 2;
  const VERSION_ERROR = 3;
  const CONNECTION_ERROR = 9;



  /**
   * Chequeamos las actualizaciones
   * @author Cody Roodaka <roodakazo@hotmail.com> && Alexander Eberle <alexander1712>
   * @return boolean
   */
  public function check_updates()
   {
    // Generamos el identificador de la web
    $myweb = md5($_SERVER['SERVER_NAME']);
    // Obtenemos los datos del servidor primario.
    $get = file_get_contents($this->server.'process.php?module=checkver&hash='.$myweb.'&version='.VERSION);
    if($get !== false)
     {
      $data = (array) json_decode(base64_decode($get), true);
      if($data['version'] === 'newver')
       {
        /* // Hay archivos modificados, se actualiza.
        if(isset($data['files']) && $data['files'] !== false)
         {
          // Obtenemos los archivos indicados uno por uno
          foreach($data['files'] as $file)
           {
            // Cargamos el contenido del nuevo archivo
            $content = base64_decode(file_get_contents($this->server.'process.php?module=fu&hash='.$myweb.'&file='.$file['name']));
            if(is_file($file['patch'].$file['name']))
             {
              // Abrimos
              $file = fopen($file['patch'].$file['name'], 'w');
              // Escribimos
              fwrite($file, $content);
              // Cerramos
              fclose($file);
             }
           }
         } */
        return self::OLD_VERSION;
       }
      // El script est� al d�a con las actualizaciones, no es necesario actualizar
      elseif($data['version'] == 'stable') { return self::LAST_VERSION; }
      // error en el servidor, se debe actualizar manualmente
      else { return self::VERSION_ERROR; }
     } else { return self::CONNECTION_ERROR; }
   } // public function check_updates();
 }