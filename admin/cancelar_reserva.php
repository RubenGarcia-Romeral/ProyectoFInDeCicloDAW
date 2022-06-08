<?php
    session_start();
    
    
    require("../modelo/ConexionDB.php");
    require("../modelo/ReservaDAO.php");
    require("../modelo/MensajesFlash.php");
    require("../modelo/Sesion.php");
    

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        
        $id = $_GET["id"];
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

        
        $resDAO = new ReservaDAO(ConexionDB::conectar());
        $reserva = $resDAO->find($id);
        
        
        if ($resDAO->delete($reserva)) {
            
            MensajesFlash::anadirMensaje("reserva-cancelada", "Reserva cancelada con exito");
            
        } else {
            
            MensajesFlash::anadirMensaje("reserva-cancelada", "Error");
            
        }
        

        header("Location: admin.php");
        
    } else {
        
        header("Location: index.php");
        
    }