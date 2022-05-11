<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');
class Paginator
 {
  /**
   * Cantidad de Páginas
   */
  protected $pages = 0;

  /**
   * Nro de páginas para mostrar
   */
  protected $show = 10;

  /**
   * Constructor de la clase
   * @param int $total Cantidad total de nodos
   * @param int $nodes_x_page Cantidad de nodos cargados por página
   * @param int $show Cantidad de Páginas a listar.
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function __construct($total, $nodes_x_page, $show)
   {
    // Calculamos la cantidad de páginas y lo seteamos
    $div = ceil($total / $nodes_x_page);
    if(($total - $nodes_x_page) >= 2 && $div == 1 ) { $this->pages = 2; }
    elseif($div > 1) { $this->pages = $div; }
    else { $this->pages = 1; }
    $this->show = $show;
   } // public function __construct();


  /**
   * Calculamos el paginado
   * @param int $page Número de página actual
   * @author Cody Roodaka <roodakazo@hotmail.com>
   * @return array Arreglo con el paginado.
   */
  public function paginate($page)
   {
    // Inicializamos el arreglo principal
    $result = array();
    // Seteamos los botones de previo e inicio
    if($page == 1) { $result['first'] = 0; }
    else { $result['first'] = 1; }

    if($page > 1) { $result['prev'] = ($page - 1); }
    else { $result['prev'] = 0; }
    // Calculamos el punto de partida para el conteo
    $start = floor($this->show / 2);
    // Nos aseguramos de que si es posible siempre arranque desde el medio
    if($start < $this->pages && $start > 0)
     {
      // indicamos que la actual estará (o lo intentará) estar en el medio.
      $calc = ($page - $start);
      // chequeamos que no sea ni negativo ni cero.
      if($calc < 1) { $c = 1; }
      else { $c = $calc; }
     }
    else
     {
      // iniciamos desde 1
      $c = 1;
     }
    // Bucle! Corremos el paginado.
    // $l indica la cantidad de páginas que se están mostrando
    // $c indica el número de página que se está mostrando
    $l = 1;
    while($l <= $this->show)
     {
      if($c <= $this->pages)
       {
        $result['pages'][] = $c;
       }
      ++$l;
      ++$c;
     }

    if($page == $this->pages)
     {
      $result['next'] = 0;
      $result['last'] = 0;
     }
    else
     {
      $result['next'] = ($page + 1);
      $result['last'] = $this->pages;
     }

    $result['self'] = $page;
    return $result;
   } // public function paginate();
 } // class Paginator();