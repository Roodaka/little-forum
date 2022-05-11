<?php defined('LFS_ROOT') or exit('No tienes Permitido el acceso.');
return array(
 // Control de niveles de acceso
 'admin_access' => array('admin_access.php',Grades::GRADE_COLAB),

  // Inicio de la administración
 'admin_main' => array('admin_main.php', Grades::GRADE_COLAB),

 // Panel Admin
 'admin_forums' => array('admin_forums.php', Grades::GRADE_COLAB),

 // Configs de la DB
 'admin_master' => array('admin_master.php', Grades::GRADE_ADMIN),

 // Panel Admin
 'admin_panel' => array('admin_panel.php', Grades::GRADE_COLAB),

 // Desloguearse de la cuenta
 'exit' => array('exit.php', Grades::GRADE_VISITOR),

 // Vista de foro
 'forum' => array('forum.php', Grades::GRADE_VISITOR),

 // Portada
 'home' => array('home.php', Grades::GRADE_VISITOR),

 // Ingreso de usuarios
 'login' => array('login.php', Grades::GRADE_VISITOR),

 // Formulario para recuperar Pass
 'lostpassword' => array('recover.php', Grades::GRADE_VISITOR),

 // Mensajería
 'messenger' => array('messenger.php', Grades::GRADE_COMMON),

 // Moderar Respuestas
 'mod_answer' => array('mod_answer.php', Grades::GRADE_LMOD),

 // Moderar Temas
 'mod_topic' => array('mod_topic.php', Grades::GRADE_LMOD),

 // Moderar Usuarios
 'mod_user' => array('mod_user.php', Grades::GRADE_GMOD),

 // Crear un nuevo tema
 'newtopic' => array('newtopic.php', Grades::GRADE_COMMON),

 // Crear una respuesta
 'newanswer' => array('newanswer.php', Grades::GRADE_COMMON),

 // Enviar un nuevo mensaje
 'newmessage' => array('newmessage.php', Grades::GRADE_COMMON),

 // Ver Perfil de usuario
 'profile' => array('profile.php', Grades::GRADE_VISITOR),

 // Buscador
 'search' => array('search.php', Grades::GRADE_VISITOR),

 // Ver los últimos temas
 'topics' => array('topics.php', Grades::GRADE_VISITOR),

 // Ver un tema
 'topic' => array('topic.php', Grades::GRADE_VISITOR),

 // Pantalla de error
 'result' => array('result.php', Grades::GRADE_VISITOR),

 // Registrar una cuenta
 'register' => array('register.php', Grades::GRADE_VISITOR),

 // Ver la lista de usuarios
 'userlist' => array('userlist.php', Grades::GRADE_VISITOR)
 );