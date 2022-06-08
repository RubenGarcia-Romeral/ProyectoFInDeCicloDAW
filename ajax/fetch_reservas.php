<?php
    require("../modelo/ConexionDB.php");
    require("../modelo/ReservaDAO.php");
    require("../modelo/HoraReservadaDAO.php");
    require("../modelo/PistaReservadaDAO.php");
    
    
    
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $conn = ConexionDB::conectar();
        
        
        $fecha = $_POST["fecha"];
        $fecha = filter_var($fecha, FILTER_SANITIZE_SPECIAL_CHARS);
        
        
        
        $resDAO = new ReservaDAO($conn);
        
        
        $reserva = $resDAO->findByFecha($fecha);
        
                    
        $json = array();
        
        
        
        if ($reserva != null) {
            
            
            $horas_reservadas = $reserva->getHoras_reservadas();
            $pistas_reservadas = $reserva->getPistas_reservadas();

            function horaTexto($hora) {
                
                switch ($hora) {
                    
                    case "09:00:00":
                        return "nueve";
                        break;
                    
                    

                    case "10:00:00":
                        return "diez";
                        break;
                    
                    

                    case "11:00:00":
                        return "once";
                        break;

                    
                    
                    case "12:00:00":
                        return "doce";
                        break;

                    
                    
                    case "13:00:00":
                        return "trece";
                        break;

                    
                    
                    case "14:00:00":
                        return "catorce";
                        break;
                    
                    
                }
            }

            
            
            
            function pistaTexto($pista) {
                switch ($pista) {
                    case "1":
                        return "pista-uno";
                        break;
                    
                    

                    case "2":
                        return "pista-dos";
                        break;
                    
                    

                    case "3":
                        return "pista-tres";
                        break;

                    
                    
                    case "4":
                        return "pista-cuatro";
                        break;
                    
                    
                }
            }

            
            
            foreach ($horas_reservadas as $hora_reservada) {
                $json[] = array(
                    
                    "hora" => horaTexto($hora_reservada->getHora_inicio())
                    
                );
            }

            
            
            foreach ($pistas_reservadas as $pista_reservada) {
                $json[] = array(
                    
                    "pista" => pistaTexto($pista_reservada->getId_pista())
                    
                );
            }
        }
        
        
        
        $jsonString = json_encode($json);
        print($jsonString);
    } else {
        header("Location: ../index.php");
    }