<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');

class View
 {
  protected $variables = array();
  protected $templates = array();
  protected $files = array(
   'scripts' => array(),
   'styles' => array());



   /**
    * Constructor de la clase.
    * @author Cody Roodaka <roodakazo@gmail.com>
    */
  public function __construct()
   {

   } // public function __construct();



   /**
    * Agregar una clave con su respectivo valor al arreglo de claves
    * @param string $key Clave a asignar
    * @param mixed $value Valor de la clave
    * @author Cody Roodaka <roodakazo@gmail.com>
    */
   public function add_key($key, $value = null)
    {
     $this->variables[$key] = $value;
    } // public function add_template();



   /**
    * Agregar una nueva plantilla para mostrar
    * @param string $template Plantilla a asignar
    * @author Cody Roodaka <roodakazo@gmail.com>
    */
   public function add_template($template)
    {
     $this->templates[] = $template;
    } // public function add_template();



   /**
    * Incluímos un archivo en el header.
    * @param string $file Archivo objetivo
    * @param string $type Tipo de archivo a incluír (js, less, css)
    * @author Cody Roodaka <roodakazo@gmail.com>
    */
   public function add_file($file, $type = 'js')
    {
     if($type === 'js') { $this->files['scripts'][] = $file; }
     elseif($type === 'css' || $type === 'less') { $this->files['styles'][] = $file; }
    } // public function add_file();



   /**
    * Mostramos o Retornamos todas las plantillas
    * @param boolean $return Indica si retornar o no el HTML generado
    * @return boolean|string Resultado
    * @author Cody Roodaka <roodakazo@gmail.com>
    */
   public function show($return = false)
    {
     // Instanciamos RainTPL
     $rain = new RainTPL();

     // Asignamos las variables
     $rain->assign($this->variables);

     // Recorremos el arreglo de plantillas y las vamos mostrando (o no)
     if($return === false)
      {
       foreach($this->templates as $template)
        {
         $rain->draw($template, false);
        }
      }
     else
      {
       $html = '';
       foreach($this->templates as $template)
        {
         $html.= $rain->draw($template, true);
        }
      }
     unset($rain);
     return ($return === false) ? true : $html;
    } // public function show();
 } // class View();