<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');
/**
 * Función para manejar las url's, de momento retorna la url tal como está
 * @param string $url URL a parsear
 * @return string URL parseada
 * @author Cody Roodaka <roodakazo@hotmail.com>
 */
function exturl($url)
 {
  return $url;
  //$result = file_get_contents('http://coinurl.com/api.php?uuid=5137543ff0cdb489132308&url='.rawurlencode($url));
  //if($result == 'error') { return false; }
  //else { return $result; }
 } // function exturl();

/**
 * CLASE PARA EL PARSEO DE CONTENIDO PUBLICADO.
 * @author Cody Roodaka <roodakazo@hotmail.com>
 */
class Parser
 {
  protected $db = null;
  // Función que redireccionaría al usuario a index.php?l=$url
  protected $urlparser = null;

  public function __construct($db)
   {
    $this->db = $db;
   } // public function __construct();

  // Parsear los BBcodes
  public function parse_bbcodes($text, $images = true)
   {
    // Expresión regular para contar el texto sin contar los bbc
    // preg_replace('/\[([^\[\]]+)\]/', '', $var)

    // Expresiones Regulares para cada BBC
    $bbc = array(
     '/(?i)\[align\=(left|center|right|justify)\]([^\a]+?)\[\/align\]/i' => '<div class="\\1">\\2</div>',  // Alineacion de texto

     '/(?i)\[b\]([^\a]+?)\[\/b\]/i' => '<b>\\1</b>',  // Negrita
     '/(?i)\[i\]([^\a]+?)\[\/i\]/i' => '<em>\\1</em>',  // Cursiva
     '/(?i)\[u\]([^\a]+?)\[\/u\]/i' => '<u>\\1</u>',  // Subrayado
     '/(?i)\[s\]([^\a]+?)\[\/s\]/i' => '<del>\\1</del>',  // Tachado

     '/(?i)\[list\]([^\a]+?)\[\/list\]/i' => '<ul class="bbc_list">\\1</ul>',  // Lista
     '/(?i)\[li\]([^\a]+?)\[\/li\]/i' => '<li>\\1</li>',  // Elemento
     '/(?i)\[olist\]([^\a]+?)\[\/olist\]/i' => '<ul class="bbc_numericlist">\\1</ul>',  // Lista numerada

     '/(?i)\[code\]([^\a]+?)\[\/code\]/i' => '<pre class="bbc_code">\\1</pre>',  // Código
     '/(?i)\[quote\]([^\a]+?)\[\/quote\]/i' => '<div class="text_quote">\\1</div>',  // Cita
     '/(?i)\[quote=([^\a]+?)\]([^\a]+?)\[\/quote\]/i' => '<div class="text_quote"><span class="user">\\1</span>\\2</div>',  // Cita
     '/(?i)\[spoiler\]([^\a]+?)\[\/spoiler\]/i' => '<div class="spoiler"><div class="spoiler_head"><input type="button" value="Spoiler" class="spoiler_button button_gray" /></div><div class="spoiler_body">\\1</div></div>',  // Spoiler

     '/(?i)\[hr]/i' => '<hr />',  // Linea horizontal
     '/(?i)\[br]/i' => '<br />'  // Nueva linea
     );

    if($images === true) { $bbc['/(?i)\[img\](http|https)?(\:\/\/)?([^\<\>[:space:]]+)\[\/img]/i'] = '<img class="bbc_img" src="\\1\\2\\3" />'; }
    // Iniciamos con el parseado
    foreach($bbc as $regex => $html)
     {
      // Nos aseguramos de parsear todas las claves
      while(preg_match($regex, $text))
       {
        $text = preg_replace($regex, $html, $text);
       }
     }

    // Parseamos los links
    while(preg_match('/(?i)\[url\=(http|https)(\:\/\/)([^\<\>[:space:]]+?)\](.+?)(\[\/url\])/i', $text, $v))
     {
      $text = preg_replace('/(?i)\[url\=(http|https)(\:\/\/)([^\<\>[:space:]]+?)\](.+?)(\[\/url\])/i', '<a class="bbc_link" href="'.exturl($v[1].$v[2].$v[3]).'" rel="nofollow" target="_blank" title="'.$v[4].'">'.$v[4].'</a>', $text);
     }

    // Parseamos las Menciones
    while(preg_match('/(?i)\[user\]([^\a]+?)\[\/user\]/i', $text, $v))
     {
      $check = $this->db->query('SELECT id FROM usuarios WHERE nick = ?', strtolower(trim($v[1])), true);
      if($check !== false)
       {
        $text = preg_replace('/(?i)\[user\]([^\a]+?)\[\/user\]/i', '<a class="bbc_mention" href="'.url('profile', $v[1]).'" title="'.$v[1].'">@'.$v[1].'</a>', $text);
       }
      else
       {
        $text = preg_replace('/(?i)\[user\]([^\a]+?)\[\/user\]/i', $v[1], $text);
       }
     }
    return $text;
   }


  public function parse_smiles($text)
   {
    $smiles = $this->db->query('SELECT letras, icono FROM '.$this->db->prefix.'emoticones', null, false);
    // Falló, no hacemos nada.
    if(!$smiles || $smiles == false) {}
    // Hay emoticones, parseamos.
    else
     {
      while($smile = $smiles->fetchrow())
       {
        $text = str_replace($smile['letras'], '<img class="smiley" src="themes/'.Master::$data['config_tema'].'/images/smiles/'.$smile['icono'].'.png">',$text);
       }
     }
    return $text;
   }

  public function parse($text, $smiles = true)
   {
    //$text = str_replace(array('<br>', '<br />'),'[br]',nl2br($text));
    if($smiles === true) { $text = $this->parse_smiles($text); }
    $text = $this->parse_bbcodes($text);
    return $text;
   }
 } // Class Parser();