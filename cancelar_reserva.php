<?php
    session_start();
    
    require("modelo/ConexionDB.php");
    require("modelo/ReservaDAO.php");
    require("modelo/MensajesFlash.php");
    require("modelo/Sesion.php");

    
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        
        if ($_POST['t'] != $_SESSION['token']) {
            MensajesFlash::anadirMensaje("error-token", "El token es incorrecto");
            
            
            
            header("Location: misreservas.php");
            
            
            
            die();
            
            
           
        } else {
            
            
            
            $id = $_POST["id"];
            $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
            
            
            
            $resDAO = new ReservaDAO(ConexionDB::conectar());
            $reserva = $resDAO->find($id);
    
            
            
            if ($reserva->getId_usuario() == Sesion::obtener()) {
                $resDAO->delete($reserva);
                
                
                MensajesFlash::anadirMensaje("reserva-cancelada", "Reserva cancelada con exito");
                
                
            } else {
                
                
                MensajesFlash::anadirMensaje("error-cancelado", "Lamentablemete, no se ha podido cancelar la reserva :(");
                
                
            }

            header("Location: misreservas.php");
        }
        
    } else {
        
        
        header("Location: index.php");
    }