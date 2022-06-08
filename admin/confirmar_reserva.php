<?php
    session_start();

    
    require("../modelo/ConexionDB.php");
    require("../modelo/Sesion.php");
    require("../modelo/Usuario.php");
    require("../modelo/UsuarioDAO.php");
    require("../modelo/ReservaDAO.php");
    require("../modelo/MensajesFlash.php");
    
    

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $conn = ConexionDB::conectar();
        
        
        $id = $_GET["id"];
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        
        
        $resDAO = new ReservaDAO($conn);
        $reserva = $resDAO->find($id);
        
        
        $reserva->setConfirmada("SI");

        
        if ($resDAO->update($reserva)) {
            
            MensajesFlash::anadirMensaje("reserva-confirmada", "Reserva hecha");
            
        } else {
            
            MensajesFlash::anadirMensaje("reserva-confirmada", "Error");
            
        }
        
        header("Location: admin.php");
        
    } else {
        
        header("Location: ../index.php");
        
    }