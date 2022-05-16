<?php
    session_start();
        
    require "modelo/ConexionDB.php";
    require "modelo/Sesion.php";
    require "modelo/Usuario.php";
    require "modelo/UsuarioDAO.php";
    require "modelo/MensajesFlash.php";
    
    

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
            
            
        } elseif ($password != filter_var($password, FILTER_SANITIZE_SPECIAL_CHARS)) {
            MensajesFlash::anadirMensaje("error-password", "El formato de la contraseña no es el adecuado");
            
            
            $error = true;
            
            
        }
        
        

        if ($error == false) {
            
            
            // Limpiamos los datos de entrada
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $password = filter_var($password, FILTER_SANITIZE_SPECIAL_CHARS);
            
            
            $usuDAO = new UsuarioDAO(ConexionDB::conectar());
            $usuario = $usuDAO->findByEmail($email);
            
            

            if ($usuario != null && $usuario->getContrasena() == password_verify($password, $usuario->getContrasena())) {
                
                
                Sesion::iniciar($usuario->getId());
                
                
                // Generamos un código aleatorio sha1 y lo guardamos en la base de datos
                
                $usuario->setCookie_id(sha1(time() + rand()));
                $usuDAO->update($usuario);
                
                setcookie("uid", $usuario->getCookie_id(), (time() + (60 * 60 * 24 * 7)));

                
                header("Location: reservas.php");
                
                
            } else {
                
                
                MensajesFlash::anadirMensaje("error-login", "No se ha encontrado el email o este no coincide con la contraseña");
                
                header("Location: index.php");
            }
            
            
        } else {
            
            header("Location: index.php");
        }
        
        
    } else {
        
        
        header("Location: index.php");
    }
    
?>