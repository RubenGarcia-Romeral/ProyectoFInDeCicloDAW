<?php
    session_start();

    
    require("../modelo/ConexionDB.php");
    require("../modelo/Sesion.php");
    require("../modelo/Usuario.php");
    require("../modelo/UsuarioDAO.php");
    require("../modelo/ReservaDAO.php");
    require("../modelo/MensajesFlash.php");
    
    

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];
        

        $error = false;
        $errores = array();
        
        

        if (empty($email)) {
            
            MensajesFlash::anadirMensaje("error-email", "Por favor, introduzca su email");
            $error = true;
            
            
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            
            MensajesFlash::anadirMensaje("error-email", "El formato del email no es el adecuado");
            $error = true;
            
        }

        if (empty($password)) {
            
            MensajesFlash::anadirMensaje("error-password", "Por favor, introduzca una contraseña");
            $error = true;
            
        }

        if ($error == false) {
            // Limpiamos los datos de entrada
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);

            $usuDAO = new UsuarioDAO(ConexionDB::conectar());
            $usuario = $usuDAO->findByEmail($email);

            if ($usuario != null && $usuario->getContrasena() == password_verify($password, $usuario->getContrasena()) && $usuario->getRol() == "admin") {
                Sesion::iniciar($usuario->getId());
            
                $usuario->setCookie_id(sha1(time() + rand()));
                $usuDAO->update($usuario);

                // Creamos la cookie en el navegador del cliente con el mismo código
                setcookie("uid", $usuario->getCookie_id(), (time() + (60 * 60 * 24 * 7)));

                header("Location: admin.php");
            } else {
                
                MensajesFlash::anadirMensaje("error-login", "No puedes acceder");

                header("Location: index.php");
                
            }
        } else {
            
            header("Location: index.php");
            
        }
    }