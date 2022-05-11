<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');
/**
 * Clase para subida de archivos al servidor.
 * Parte de Little forum Script
 * @author Cody Roodaka <roodakazo@gmail.com>
 */
class Uploader
 {
  // Directorio en donde se copiará el archivo
  protected $path = '/';
  // Tamaño máximo, en bytes
  protected $max_size = 5120;
  // Ancho máximo, en píxeles
  protected $max_width = 200;
  // Alto máximo, en píxeles
  protected $max_height = 200;
  // Tipos de archivo permitidos
  protected $allow_filetypes = array();
  // Nombre del archivo ya copiado
  protected $futurename = '';
  // Error
  public $error = '';
  // Ubicacion completa del archivo subido
  public $result = '';
  // Archivo por defecto
  public $default_file = '';
  


  /**
   * Inicializamos la clase Uploader
   * @param string $target_path Directorio donde se copiará la imagen.
   * @param string $name Nuevo nombre del archivo copiado
   * @param int $max_size Tamaño máximo del archivo en bytes
   * @param int $max_width Ancho máximo del archivo en pixeles
   * @param int $max_height Alto máximo del archivo en pixeles
   * @param array $allow_filetypes Filtro de tipos de archivo a subir
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function __construct($target_path, $futurename, $max_size, $max_width, $max_height, $allow_filetypes = array(), $default = null)
   {
    $this->path = $target_path;
    $this->max_size = $max_size;
    $this->max_width = $max_width;
    $this->max_height = $max_height;
    $this->allow_filetypes = $allow_filetypes;
    $this->futurename = $futurename;
    $this->default_file = $default;
   } // public functiom __construct


  /**
   * Subimos la imagen
   * @param array $file Arreglo con los datos del archivo temporal objetivo
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com> & Alexander Eberle
   */
  public function upload_file($file = array())
   {
    // Filtro por contenido vacío
    if(!empty($file['tmp_name']) || is_uploaded_file($file['tmp_name']) == true)
     {
      // Filtro de encabezado
      if(isset($this->allow_filetypes[$file['type']]))
       {
        $imaginfo = getimagesize($file['tmp_name']);
        // Filtro por estructura
        if(isset($this->allow_filetypes[$imaginfo['mime']]))
         {
          // Chequeamos que tenga el tamaño requerido
          if(filesize($file['tmp_name']) <= $this->max_size)
           {
            // Comprobamos que tenga el tamaño indicado
            if($imaginfo[0] <= $this->max_width && $imaginfo[1] <= $this->max_height)
             {
              // Copiamos el archivo final
              $copy = copy($file['tmp_name'],$this->path.$this->futurename.$this->allow_filetypes[$imaginfo['mime']]);
              if($copy == true)
               {
                $this->error = false;
                $this->result = $this->path.$this->futurename.$this->allow_filetypes[$imaginfo['mime']];
                return true;
               }
              else { $this->error = 'copy'; }
             }
            else { $this->error = 'imagesize'; }
           }
          else { $this->error = 'filesize'; }
         }
        else { $this->error = 'mime'; }
       }
      else { $this->error = 'exthead'; }
     }
    else { $this->error = 'empty'; }
    return false;
   } // public function upload_file



  /**
   * Cargamos la imagen desde una URL externa
   * @param string $mail Mail objetivo
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function use_url($url)
   {
    // Nro de caracteres de la url
    $long = strlen($url);

    // Guardamos solo la extension del archivo
    $ext = substr($url, ($long - 4), $long);

    $imaginfo = getimagesize($url);
    if(isset($this->allow_filetypes[$imaginfo['mime']]))
     {
      if($imaginfo[0] <= $this->max_width && $imaginfo[1] <= $this->max_height)
       {
        $this->result = $url;
        return true;
       }
      else { $this->error = 'imagesize'; return false; }
     }
    else { $this->error = 'mime'; return false; }
   } // public function use_url();



  /**
   * Usamos una URL de gravatar.
   * @param string $mail Mail objetivo
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function use_gravatar($mail)
   {
    $this->result = 'http://www.gravatar.com/avatar/'.md5(strtolower($mail)).'?s=120';
    return true;
   } // public function use_gravatar();



  /**
   * Seleccionamos el valor por defecto para los avatares.
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function use_default()
   {
    $this->result = $this->default_file;
    return true;
   }

 } // class Uploader();