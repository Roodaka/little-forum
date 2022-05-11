<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');


class Categorias
 {
  /**
   * Instancia de LittleDB
   */
  protected $db = null;


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
   * Obtener las categorías
   * @return array Lista de categorías
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function get_categories()
   {
    $query = $this->db->query('SELECT id, nombre, orden, nivel FROM '.$this->db->prefix.'categorias ORDER BY orden ASC, nombre ASC', null, false);
    if($query !== false)
     {
      // Inicializamos y armamos el arreglo
      $cats = array();
      while($cat = $query->fetchrow())
       {
        $cats[] = $cat;
       }
      return $cats;
     }
   } // public function get_categories();



  /**
   *
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function get_category($id)
   {
    return $this->db->query('SELECT id, nombre, nivel, orden FROM '.$this->db->prefix.'categorias WHERE id = ? LIMIT 1', $id, true);
   } // public function get_category();



  /**
   * Creamos una categoría
   * @param string $name Nombre de la categoría
   * @param int $level Nivel de acceso requerido para ver la categoría
   * @param int $order Posición
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function new_category($name, $level, $order)
   {
    $insert = $this->db->insert('categorias', array(
     'nombre' => htmlspecialchars($name),
     'nivel' => (int) $level,
     'orden' => (int) $order
     ), true);
    if($insert !== false) { return $this->reorder((int) $insert, ($order + 1)); }
   } // public function new_category();



  /**
   * Actualizamos una categoría
   * @param int $id ID de la categoría objetivo
   * @param string $name Nombre de la categoría
   * @param int $level Nivel de acceso requerido para ver la categoría
   * @param int $order Nueva posición, nulo si no cambia.
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function update_category($id, $name, $level, $order = null)
   {
    $update = $this->db->update('categorias', array( 'nombre' => htmlspecialchars($name), 'nivel' => (int) $level), array('id' => (int) $id), false);
    if($update !== false) { return (($order !== null) ? $this->reorder($id, $order) : true); }
   } // public function update_category();



  /**
   * Borramos una categoría y movemos sus foros a otra.
   * @param int $id ID de la categoría a borrar
   * @param int $target Categoría a donde movemos los foros
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function delete_category($id, $target)
   {
    $del = $this->db->delete('categorias', array('id' => $id), false);
    $upd = $this->db->update('foros', array('cat_id' => $target), array('cat_id' => $id), false);
    if($upd == false || $del == false) { return false; }
    else { return true; }
   } // public function delete_category();



  /**
   * Reacomodamos una categoría en cierta posición.
   * @param int $id ID de la categoría objetivo
   * @param int $position Nueva posición
   * @return boolean
   * @author Cody Roodaka <roodakazo@hotmail.com>
   */
  public function reorder($id, $position)
   {
    // Si hay una categoría con la posición que buscamos, recorremos las
    // categorías hasta actualizarlas todas.
    $query = $this->db->query('SELECT id FROM categorias WHERE orden = ? LIMIT 1', $position, TRUE);
    if($query !== false) { $this->reorder($query['id'], ($position + 1)); }

    // Retornamos la actualización de la categoría
    return $this->db->update('categorias', array('orden' => (int) $position), array('id' => (int)$id), false);
   } // public function reorder();

 } // class Categorias();