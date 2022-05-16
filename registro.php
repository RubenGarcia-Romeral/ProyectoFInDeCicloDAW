<?php
    session_start();
    
    require "modelo/ConexionDB.php";
    require "modelo/Sesion.php";
    require "modelo/Usuario.php";
    require "modelo/UsuarioDAO.php";
    require "modelo/MensajesFlash.php";
    
    

    if (isset($_COOKIE['uid']) || (Sesion::existe())) {
        
        
        $uid = filter_var($_COOKIE['uid'], FILTER_SANITIZE_SPECIAL_CHARS);

        $usuarioDAO = new UsuarioDAO(ConexionDB::conectar());

        $usuario = $usuarioDAO->findByCookieId($uid);

        if ($usuario != false) {
            Sesion::iniciar($usuario->getId());

            header("Location: reservas.php");
            
            
        }
    }
    
    

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['token'] != $_SESSION['token']) {
            MensajesFlash::anadirMensaje("error-token", "Token incorrecto.");
            header("Location: index.php");
        }
        
        $usuDAO = new UsuarioDAO(ConexionDB::conectar());
        
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        
        if (isset($_POST['foto'])) {
            $foto = $_POST['foto'];
        }

        $error = false;
        $errores = array();

        
        if (empty($nombre)) {
            MensajesFlash::anadirMensaje("error-nombre", "Por favor, introduzca su nombre");
            $error = true;
        } elseif ($nombre != filter_var($nombre, FILTER_SANITIZE_SPECIAL_CHARS)) {
            MensajesFlash::anadirMensaje("error-nombre", "El formato del nombre no es el adecuado");
            $error = true;
        }
        
        if (empty($apellidos)) {
            MensajesFlash::anadirMensaje("error-apellidos", "Por favor, introduzca sus apellidos.");
            $error = true;
        } elseif ($apellidos != filter_var($apellidos, FILTER_SANITIZE_SPECIAL_CHARS)) {
            MensajesFlash::anadirMensaje("error-apellidos", "El formato de los apellidos no es el adecuado");
            $error = true;
        }
        
        if (empty($email)) {
            MensajesFlash::anadirMensaje("error-email-registro", "Por favor, introduzca su email");
            $error = true;
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            MensajesFlash::anadirMensaje("error-email-registro", "El formato del email no es el adecuado");
            $error = true;
        } elseif ($usuDAO->findByEmail($email) != null) {
            MensajesFlash::anadirMensaje("error-email-registro", "El email introducido ya esta registrado en nuestra página");
            $error = true;
        }
        
        if (empty($telefono)) {
            MensajesFlash::anadirMensaje("error-telefono", "Por favor, introduzca su numero de telefono");
            $error = true;
        } elseif ($telefono != filter_var($telefono, FILTER_SANITIZE_NUMBER_INT)) {
            MensajesFlash::anadirMensaje("error-telefono", "El formato del número de teléfono no es el adecuado");
            $error = true;
        }
        
        if (empty($password)) {
            MensajesFlash::anadirMensaje("error-password", "Por favor, introduzca una contraseña");
            $error = true;
        } elseif ($password != filter_var($password, FILTER_SANITIZE_SPECIAL_CHARS)) {
            MensajesFlash::anadirMensaje("error-password", "El formato de la contraseña no es el adecuado");
            $error = true;
        } elseif ($password != $password2) {
            MensajesFlash::anadirMensaje("error-password", "Las contraseñas no coinciden");
            $error = true;
        }

        if ($_FILES['foto']['error'] == 0) {
            if ($_FILES['foto']['type'] != 'image/png' &&
                $_FILES['foto']['type'] != 'image/gif' &&
                $_FILES['foto']['type'] != 'image/jpeg') {
                MensajesFlash::anadirMensaje("error-foto", "El archivo seleccionado no es una foto.");
                $error = true;
            }

            if ($_FILES['foto']['size'] > 1000000) {
                MensajesFlash::anadirMensaje("error-foto", "El archivo seleccionado es demasiado grande");
                $error = true;
            }
        }

        if ($error == false) {
            $usuario = new Usuario();
            
            if ($_FILES['foto']['error'] == 0) {
                // Copiar foto

                // Generamos un nombre para la foto
                $nombre_foto = md5(time() + rand(0, 999999));
                $extension_foto = substr($_FILES['foto']['name'], strrpos($_FILES['foto']['name'], '.') + 1);
                $extension_foto = filter_var($extension_foto, FILTER_SANITIZE_SPECIAL_CHARS);

                // Comprobamos que no exista ya una foto con el mismo nombre, si existe calculamos uno nuevo
                while (file_exists("imagenes/fotosdeperfil/$nombre_foto.$extension_foto")) {
                    $nombre_foto = md5(time() + rand(0, 999999));
                }
                
                // Creamos un objeto GdImage para redimensionar la foto
                if ($_FILES['foto']['type'] == 'image/png') {
                    $imagen = imagecreatefrompng($_FILES['foto']['tmp_name']);
                } elseif ($_FILES['foto']['type'] == 'image/gif') {
                    $imagen = imagecreatefromgif($_FILES['foto']['tmp_name']);
                } elseif ($_FILES['foto']['type'] == 'image/jpeg') {
                    $imagen = imagecreatefromjpeg($_FILES['foto']['tmp_name']);
                }
                
                list($width, $height) = getimagesize($_FILES['foto']['tmp_name']);
                
                $imagen = imagescale($imagen, ((150 * $width) / $height), 150);

                // Movemos la foto a la carpeta que queramos guardarla y con el nombre original
                imagejpeg($imagen, "imagenes/fotosdeperfil/$nombre_foto.$extension_foto", 100);
                $usuario->setFoto("$nombre_foto.$extension_foto");
            }

            // Limpiamos los datos de entrada
            $nombre = filter_var($nombre, FILTER_SANITIZE_SPECIAL_CHARS);
            $apellidos = filter_var($apellidos, FILTER_SANITIZE_SPECIAL_CHARS);
            $telefono = filter_var($telefono, FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $password = filter_var($password, FILTER_SANITIZE_SPECIAL_CHARS);
            $password = password_hash($password, PASSWORD_BCRYPT);

            $usuario->setNombre($nombre);
            $usuario->setApellidos($apellidos);
            $usuario->setTelefono($telefono);
            $usuario->setEmail($email);
            $usuario->setContrasena($password);
            
            $usuDAO->insert($usuario);

            Sesion::iniciar($usuario->getId());
          
            $usuario->setCookie_id(sha1(time() + rand()));
            $usuDAO->update($usuario);

            setcookie("uid", $usuario->getCookie_id(), (time() + (60 * 60 * 24 * 7)));
            header("Location: reservas.php");
        }
    }
    

    $token = md5(time() + rand (0, 999));
    $_SESSION['token'] = $token;
?>

<!DOCTYPE html>
<html lang="es-ES">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href="css/estilos.css" rel="stylesheet">
</head>
<body>
   

    <form id="registro" action="" method="post" enctype="multipart/form-data">
         <h1>Formulario de registro</h1>
         <br>   
         
        <input type="hidden" name="token" value="<?= $token ?>">
        
        <input type="text" name="nombre" placeholder="Nombre" value="<?php if (isset($nombre)) print($nombre) ?>"><br>
        <?=MensajesFlash::imprimirMensaje("error-nombre"); ?>

        <input type="text" name="apellidos" placeholder="Apellidos" value="<?php if (isset($apellidos)) print($apellidos) ?>"><br>
        <?=MensajesFlash::imprimirMensaje("error-apellidos"); ?>
                
        <input type="text" name="email" placeholder="email" value="<?php if (isset($email)) print($email) ?>"><br>
        <?=MensajesFlash::imprimirMensaje("error-email-registro"); ?>

        <input type="text" name="telefono" placeholder="Telefono" value="<?php if (isset($telefono)) print($telefono) ?>"><br>
        <?=MensajesFlash::imprimirMensaje("error-telefono"); ?>

        <input type="password" name="password" placeholder="Contraseña"><br>
        <?=MensajesFlash::imprimirMensaje("error-password"); ?>

        <input type="password" name="password2" placeholder="Repita la contraseña"><br>

        <br>
        <label id="botonfoto">
            Seleccionar foto de perfil
            
            <input type="file" name="foto" accept="image/*">
        </label>
        
        <?=MensajesFlash::imprimirMensaje("error-foto"); ?>
        
        <br>
        <br>
        
        <button type="submit" form="registro" id="boton">Registrarse</button>
        <?=MensajesFlash::imprimirMensaje("error-token"); ?>

        <br>

        <a href="index.php" id="urlregistro">Volver al login</a>
    </form>
</body>
</html>