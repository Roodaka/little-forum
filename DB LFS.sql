-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-07-2013 a las 19:22:59
-- Versión del servidor: 5.5.19-log
-- Versión de PHP: 5.4.0RC4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `cody`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE IF NOT EXISTS `cache` (
  `clave` varchar(64) COLLATE utf8_bin NOT NULL,
  `tipo` int(1) NOT NULL,
  `contenido` text COLLATE utf8_bin NOT NULL,
  `fechahora` int(10) NOT NULL,
  PRIMARY KEY (`clave`),
  UNIQUE KEY `clave` (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) COLLATE utf8_bin NOT NULL,
  `nivel` int(10) DEFAULT NULL,
  `orden` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `nivel`, `orden`) VALUES
(1, 'General', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `clave` varchar(255) COLLATE utf8_bin NOT NULL,
  `valor` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `config`
--

INSERT INTO `config` (`clave`, `valor`) VALUES
('ads_enable', '0'),
('ads_html_footer', ''),
('ads_html_header', '<iframe scrolling="no" style="border: 0; width: 468px; height: 60px;" src="http://coinurl.com/get.php?id=3449"></iframe>'),
('ads_html_leftbar', '<iframe scrolling="no" style="border: 0; width: 200px; height: 200px;" src="http://coinurl.com/get.php?id=3450"></iframe>'),
('cache_life', '300'),
('cache_mode', 'none'),
('captcha_answer', '1'),
('captcha_dir', 'files/captcha.jpeg'),
('captcha_login', '1'),
('captcha_mp', '1'),
('captcha_newtopic', '1'),
('captcha_register', '1'),
('cookie_life', '604800'),
('cookie_name', 'lfs'),
('cookie_path', '/'),
('enable_cookies', '1'),
('enable_register', '1'),
('enable_search', '1'),
('enable_sign', '1'),
('enable_smiles', '1'),
('grade_default_new_user', '2'),
('grade_default_visitor', '1'),
('mail_sendtype', 'mail'),
('mail_smtp_password', 'password'),
('mail_smtp_port', '21'),
('mail_smtp_server', 'smtp.littleforum.com.ar'),
('mail_user', 'user'),
('maintenance_comment', ''),
('maintenance_title', 'Mantenimiento del Foro.'),
('news_toshow', '1'),
('news_userscandisable', ''),
('pagelimit_answers', '10'),
('pagelimit_mps', '20'),
('pagelimit_nodes', '10'),
('pagelimit_topics', '20'),
('pagelimit_users', '20'),
('polls_levelreq', '6'),
('polls_maxoptions', '5'),
('sign_bbc', '1'),
('sign_images', '1'),
('sign_maxchars', '255'),
('sign_smiles', '1'),
('site_defaultlang', '1'),
('site_defaulttheme', 'future'),
('site_desc', 'Soporte Oficial'),
('site_host', '127.0.0.1'),
('site_maintenance', ''),
('site_patch', '/'),
('site_start', '1336660063'),
('site_tags', 'comunidad, foro, script, opensource, gratis, forum, free, codigo, foros, open, soporte'),
('site_timemode', 'd/m/Y - H:i:s'),
('site_timezone', 'America/Argentina/Buenos_Aires'),
('site_title', 'Little Forum'),
('uploader_avatar_default', 'files/avatars/default.gif'),
('uploader_avatar_filetypes', '{"image\\/png":".png","image\\/jpg":".jpg","image\\/jpeg":".jpeg"}'),
('uploader_avatar_mode', 'file'),
('uploader_max_height', '500'),
('uploader_max_size', '3145728'),
('uploader_max_width', '500'),
('user_approbed_posts', '25'),
('user_change_lang', '1'),
('user_change_theme', '1'),
('user_connected_range', '900'),
('user_max_age', '99'),
('user_min_age', '14'),
('user_need_activation', '1'),
('user_registered_default_grade', '2'),
('user_session_life', '86400'),
('user_visitor_default_grade', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cookies`
--

CREATE TABLE IF NOT EXISTS `cookies` (
  `hash` varchar(32) COLLATE utf8_bin NOT NULL,
  `user_id` int(10) NOT NULL,
  `nav` varchar(32) COLLATE utf8_bin NOT NULL,
  `ip` int(10) NOT NULL,
  `datetime` int(10) NOT NULL,
  PRIMARY KEY (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `emoticones`
--

CREATE TABLE IF NOT EXISTS `emoticones` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) COLLATE utf8_bin NOT NULL,
  `letras` varchar(10) COLLATE utf8_bin NOT NULL,
  `icono` varchar(20) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadisticas`
--

CREATE TABLE IF NOT EXISTS `estadisticas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `temas` int(10) NOT NULL,
  `mensajes` int(10) NOT NULL,
  `usuarios` int(10) NOT NULL,
  `ultimousuario` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `estadisticas`
--

INSERT INTO `estadisticas` (`id`, `temas`, `mensajes`, `usuarios`, `ultimousuario`) VALUES
(1, 34, 10, 18, 18);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `foros`
--

CREATE TABLE IF NOT EXISTS `foros` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cat_id` int(10) NOT NULL,
  `padre_id` int(10) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_bin NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_bin NOT NULL,
  `tipo` int(1) NOT NULL DEFAULT '0' COMMENT '0:normal, 1:redireccion, 2:vacio',
  `redireccion` text COLLATE utf8_bin,
  `hits` int(10) DEFAULT NULL,
  `nivel_ver` int(10) NOT NULL DEFAULT '1',
  `nivel_crear` int(10) NOT NULL DEFAULT '2',
  `temas` int(4) NOT NULL,
  `respuestas` int(4) NOT NULL,
  `ultimo_tema` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `foros`
--

INSERT INTO `foros` (`id`, `cat_id`, `padre_id`, `nombre`, `descripcion`, `tipo`, `redireccion`, `hits`, `nivel_ver`, `nivel_crear`, `temas`, `respuestas`, `ultimo_tema`) VALUES
(1, 1, 0, 'Foro de Muestra', 'Descripci&oacute;n aqu&iacute;.', 0, NULL, 0, 1, 2, 3, 0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `foros_condicion`
--

CREATE TABLE IF NOT EXISTS `foros_condicion` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `foro_id` int(10) NOT NULL,
  `tipo` int(1) NOT NULL,
  `objeto_id` int(10) NOT NULL,
  `autoriza_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `foros_leidos`
--

CREATE TABLE IF NOT EXISTS `foros_leidos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `foro_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `fechahora` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `foros_leidos`
--

INSERT INTO `foros_leidos` (`id`, `foro_id`, `user_id`, `fechahora`) VALUES
(1, 2, 1, 1363270004),
(2, 23, 1, 1368294175);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `foros_moderadores`
--

CREATE TABLE IF NOT EXISTS `foros_moderadores` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `foro_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `autoriza` int(10) NOT NULL,
  `fechahora` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lenguajes`
--

CREATE TABLE IF NOT EXISTS `lenguajes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) COLLATE utf8_bin NOT NULL,
  `clave` varchar(10) COLLATE utf8_bin NOT NULL,
  `meta` varchar(10) COLLATE utf8_bin NOT NULL,
  `version` varchar(10) COLLATE utf8_bin NOT NULL,
  `archivo` varchar(10) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `lenguajes`
--

INSERT INTO `lenguajes` (`id`, `nombre`, `clave`, `meta`, `version`, `archivo`) VALUES
(1, 'Espa&ntilde;ol', 'es-AR', 'spanish', '0.1', 'es');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE IF NOT EXISTS `mensajes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `autor_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `titulo` varchar(100) COLLATE utf8_bin NOT NULL,
  `contenido` text COLLATE utf8_bin NOT NULL,
  `contenido_html` text COLLATE utf8_bin NOT NULL,
  `borrado_autor` int(1) NOT NULL,
  `borrado_receptor` int(1) NOT NULL,
  `leido` int(1) NOT NULL,
  `fechahora` int(10) NOT NULL,
  `ip` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `mensajes`
--

INSERT INTO `mensajes` (`id`, `autor_id`, `user_id`, `titulo`, `contenido`, `contenido_html`, `borrado_autor`, `borrado_receptor`, `leido`, `fechahora`, `ip`) VALUES
(1, 1, 6, 'Holaza', 'asdfsdgd gsdjofbn ', '', 0, 0, 0, 1340862068, 0),
(2, 1, 6, '', 'Hola Alex!', 'Hola Alex!', 0, 0, 0, 1364500085, 2130706433);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias`
--

CREATE TABLE IF NOT EXISTS `noticias` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `contenido` text COLLATE utf8_bin NOT NULL,
  `user_id` int(10) NOT NULL,
  `fechahora` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `noticias`
--

INSERT INTO `noticias` (`id`, `contenido`, `user_id`, `fechahora`) VALUES
(1, 'Buenas, soy una noticia.', 1, 1347511438);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preferencias`
--

CREATE TABLE IF NOT EXISTS `preferencias` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `clave` varchar(70) COLLATE utf8_bin NOT NULL,
  `valor` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rangos`
--

CREATE TABLE IF NOT EXISTS `rangos` (
  `id` int(10) NOT NULL,
  `nivel_acceso` int(2) NOT NULL,
  `tipo` bit(1) NOT NULL DEFAULT b'0',
  `cantidad` int(4) NOT NULL DEFAULT '0',
  `nombre` varchar(25) COLLATE utf8_bin NOT NULL,
  `color` varchar(6) COLLATE utf8_bin NOT NULL,
  `bold` bit(1) NOT NULL,
  `icono` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `usuarios` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `rangos`
--

INSERT INTO `rangos` (`id`, `nivel_acceso`, `tipo`, `cantidad`, `nombre`, `color`, `bold`, `icono`, `usuarios`) VALUES
(1, 1, b'1', 0, 'Visitante', '646464', b'0', 'grades/user_silhouette.png', 0),
(2, 2, b'1', 0, 'Usuario', '035488', b'1', 'grades/user.png', 0),
(3, 3, b'1', 0, 'Usuario V.I.P', 'FFC90E', b'1', 'grades/coins.png', 0),
(6, 6, b'1', 0, 'Moderador', 'cc00cc', b'0', 'grades/shield.png', 0),
(7, 7, b'1', 0, 'Moderador Global', '008000', b'1', 'grades/crown_bronze.png', 0),
(8, 8, b'1', 0, 'Colaborador', 'cc0000', b'1', 'grades/crown_silver.png', 0),
(9, 9, b'1', 0, 'Administrador', '990000', b'1', 'grades/crown.png', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rangos_acciones`
--

CREATE TABLE IF NOT EXISTS `rangos_acciones` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nivel_acceso` int(10) NOT NULL,
  `accion` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=128 ;

--
-- Volcado de datos para la tabla `rangos_acciones`
--

INSERT INTO `rangos_acciones` (`id`, `nivel_acceso`, `accion`) VALUES
(13, 2, 2),
(14, 8, 1),
(15, 8, 2),
(16, 8, 3),
(17, 8, 4),
(18, 8, 5),
(19, 8, 6),
(20, 8, 7),
(21, 8, 8),
(22, 8, 9),
(23, 8, 10),
(24, 8, 11),
(25, 8, 12),
(26, 8, 30),
(27, 8, 31),
(28, 8, 32),
(29, 8, 33),
(30, 8, 34),
(31, 8, 35),
(32, 8, 36),
(33, 8, 37),
(34, 8, 38),
(35, 8, 50),
(36, 8, 51),
(37, 8, 70),
(38, 9, 1),
(39, 9, 2),
(40, 9, 3),
(41, 9, 4),
(42, 9, 5),
(43, 9, 6),
(44, 9, 7),
(45, 9, 8),
(46, 9, 9),
(47, 9, 10),
(48, 9, 11),
(49, 9, 12),
(50, 9, 30),
(51, 9, 31),
(52, 9, 32),
(53, 9, 33),
(54, 9, 34),
(55, 9, 35),
(56, 9, 36),
(57, 9, 37),
(58, 9, 38),
(59, 9, 50),
(60, 9, 51),
(61, 9, 70),
(62, 9, 71),
(63, 9, 72),
(64, 9, 73),
(65, 9, 74),
(66, 7, 1),
(67, 7, 2),
(68, 7, 3),
(69, 7, 4),
(70, 7, 5),
(71, 7, 6),
(72, 7, 7),
(73, 7, 8),
(74, 7, 9),
(75, 7, 10),
(76, 7, 11),
(77, 7, 12),
(78, 7, 30),
(79, 7, 31),
(80, 7, 32),
(81, 7, 33),
(82, 7, 34),
(83, 7, 35),
(84, 7, 36),
(85, 7, 37),
(86, 7, 38),
(87, 7, 50),
(88, 7, 51),
(89, 6, 1),
(90, 6, 2),
(91, 6, 3),
(92, 6, 4),
(93, 6, 5),
(94, 6, 7),
(95, 6, 8),
(96, 6, 9),
(97, 6, 10),
(98, 6, 11),
(99, 6, 12),
(100, 6, 30),
(101, 6, 31),
(102, 6, 33),
(103, 6, 34),
(104, 6, 35),
(105, 6, 36),
(106, 6, 37),
(107, 2, 1),
(108, 2, 4),
(109, 2, 5),
(110, 2, 10),
(111, 2, 11),
(112, 2, 12),
(113, 2, 30),
(114, 2, 31),
(115, 2, 34),
(116, 3, 1),
(117, 3, 2),
(118, 3, 3),
(119, 3, 4),
(120, 3, 5),
(121, 3, 10),
(122, 3, 11),
(123, 3, 12),
(124, 3, 30),
(125, 3, 31),
(126, 3, 34),
(127, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recuperar`
--

CREATE TABLE IF NOT EXISTS `recuperar` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `hash` varchar(64) COLLATE utf8_bin NOT NULL,
  `fechahora` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

CREATE TABLE IF NOT EXISTS `reportes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tipo` int(2) NOT NULL COMMENT '0:user, 1:topic, 2:answer, 4:shout, 5:shout_answer',
  `objeto_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `comentario` varchar(255) COLLATE utf8_bin NOT NULL,
  `fechahora` int(10) NOT NULL,
  `ip` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sansiones`
--

CREATE TABLE IF NOT EXISTS `sansiones` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `moderador` int(10) NOT NULL,
  `razon` text COLLATE utf8_bin NOT NULL,
  `fechahora` int(10) NOT NULL,
  `duracion` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesiones`
--

CREATE TABLE IF NOT EXISTS `sesiones` (
  `hash` varchar(32) COLLATE utf8_bin NOT NULL,
  `user_id` int(10) NOT NULL,
  `nav` varchar(32) COLLATE utf8_bin NOT NULL,
  `ip` int(10) NOT NULL,
  `datetime` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `temas`
--

CREATE TABLE IF NOT EXISTS `temas` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `foro_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `fijado` int(1) NOT NULL,
  `tipo` int(10) DEFAULT NULL,
  `titulo` varchar(50) COLLATE utf8_bin NOT NULL,
  `contenido` text COLLATE utf8_bin NOT NULL,
  `contenido_html` text COLLATE utf8_bin NOT NULL,
  `estado` int(1) NOT NULL,
  `prefijo` int(1) NOT NULL DEFAULT '1',
  `comentar` int(1) NOT NULL,
  `firmas` int(1) NOT NULL,
  `ip` int(10) NOT NULL,
  `fechahora` int(10) NOT NULL,
  `ultima_respuesta` int(10) DEFAULT NULL,
  `respuestas` int(4) NOT NULL,
  `visitas` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `titulo` (`titulo`,`contenido`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `temas`
--

INSERT INTO `temas` (`id`, `foro_id`, `user_id`, `fijado`, `tipo`, `titulo`, `contenido`, `contenido_html`, `estado`, `prefijo`, `comentar`, `firmas`, `ip`, `fechahora`, `ultima_respuesta`, `respuestas`, `visitas`) VALUES
(1, 1, 1, 0, 0, 'Me presento', 'Hola soy cody :D (?', 'Hola soy cody :D (?', 1, 0, 1, 1, 2130706433, 1369496938, NULL, 0, 1),
(2, 1, 1, 0, 0, 'Me presento', 'Holaz', 'Holaz', 1, 0, 1, 1, 2130706433, 1369497015, NULL, 0, 0),
(3, 1, 1, 0, 0, 'Me presento', 'Holaz', 'Holaz', 1, 0, 1, 1, 2130706433, 1369497125, NULL, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `temas_respuestas`
--

CREATE TABLE IF NOT EXISTS `temas_respuestas` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `tema_id` int(10) NOT NULL,
  `estado` int(1) NOT NULL,
  `contenido` text COLLATE utf8_bin NOT NULL,
  `contenido_html` text COLLATE utf8_bin NOT NULL,
  `fechahora` int(10) NOT NULL,
  `ip` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `temas_respuestas_votos`
--

CREATE TABLE IF NOT EXISTS `temas_respuestas_votos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `answ_id` int(10) NOT NULL,
  `fechahora` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `answ_id` (`answ_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `temas_tipos`
--

CREATE TABLE IF NOT EXISTS `temas_tipos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(10) COLLATE utf8_bin NOT NULL,
  `color` varchar(6) COLLATE utf8_bin NOT NULL,
  `icono` varchar(20) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=10 ;

--
-- Volcado de datos para la tabla `temas_tipos`
--

INSERT INTO `temas_tipos` (`id`, `nombre`, `color`, `icono`) VALUES
(1, 'Sugerencia', '009900', 'hand'),
(2, 'Mod', '990000', 'plugin'),
(3, 'Noticia', 'FFA000', 'newspaper'),
(4, 'Theme', '000099', 'color_swatch_1'),
(5, 'Fix', '008000', 'hammer'),
(6, 'Pedido', '800000', 'note'),
(8, 'Script', '990000', 'page_white_php'),
(9, 'Error', '990000', 'bug');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `temas_visitas`
--

CREATE TABLE IF NOT EXISTS `temas_visitas` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tema_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `mode` int(1) NOT NULL DEFAULT '1',
  `fechahora` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `temas_visitas`
--

INSERT INTO `temas_visitas` (`id`, `tema_id`, `user_id`, `mode`, `fechahora`) VALUES
(1, 3, 1, 1, 1369497603),
(2, 1, 1, 1, 1370265639);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `temas_votos`
--

CREATE TABLE IF NOT EXISTS `temas_votos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tema_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `modo` int(1) NOT NULL DEFAULT '0',
  `fechahora` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tema_id` (`tema_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nick` varchar(20) COLLATE utf8_bin NOT NULL,
  `nombre` varchar(50) COLLATE utf8_bin NOT NULL,
  `contrasenia` varchar(64) COLLATE utf8_bin NOT NULL,
  `mail` varchar(255) COLLATE utf8_bin NOT NULL,
  `avatar` varchar(255) COLLATE utf8_bin NOT NULL,
  `estado` int(2) NOT NULL,
  `rango_id` int(10) NOT NULL,
  `mensajes` int(10) NOT NULL,
  `ip_registro` int(10) NOT NULL,
  `fecharegistro` int(10) NOT NULL,
  `ultimaip` int(10) DEFAULT NULL,
  `ultimafecha` int(10) DEFAULT NULL,
  `config_idioma` int(10) NOT NULL,
  `config_tema` varchar(10) COLLATE utf8_bin NOT NULL,
  `config_show_mail` int(1) NOT NULL,
  `fechanacimiento` int(10) DEFAULT NULL,
  `sexo` int(1) DEFAULT NULL,
  `firma` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `firma_html` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `web` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `ubicacion` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `biografia` text COLLATE utf8_bin,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nick`, `nombre`, `contrasenia`, `mail`, `avatar`, `estado`, `rango_id`, `mensajes`, `ip_registro`, `fecharegistro`, `ultimaip`, `ultimafecha`, `config_idioma`, `config_tema`, `config_show_mail`, `fechanacimiento`, `sexo`, `firma`, `firma_html`, `web`, `ubicacion`, `biografia`) VALUES
(1, 'Roodaka', 'Cody Roodaka', '$2a$08$Zytx06m8ZR7tnQOKKxv7dORf2GFOXyuofR9Itt/orLHt/1bXCge0q', 'roodakazo@gmail.com', 'files/avatars/default.gif', 1, 9, 3, 2130706433, 1369359879, 2130706433, 1372812356, 1, 'future', 0, 779770800, 1, 'Atte. [url=http://roodaka.net]Cody Roodaka[/url].', 'Atte. <a class="bbc_link" href="http://roodaka.net" rel="nofollow" target="_blank" title="Cody Roodaka">Cody Roodaka</a>.', '', 'Santa Clara del Mar, Buenos Aires, Argentina', 'Tengo 18 aÃ±os, soy un aspirante a Ingeniero, me gusta el rock pesado, no atiendo tele ni radio y siempre estoy preparado con mis herramientas y mi cloroformo.\r\n');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
