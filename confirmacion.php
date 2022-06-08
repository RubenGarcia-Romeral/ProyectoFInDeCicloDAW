<?php
session_start();

require("modelo/ConexionDB.php");
require("modelo/Sesion.php");
require("modelo/Usuario.php");
require("modelo/UsuarioDAO.php");
require("modelo/ReservaDAO.php");
require("modelo/HoraReservadaDAO.php");
require("modelo/PistaReservadaDAO.php");
require("modelo/MensajesFlash.php");




if (!isset($_COOKIE['uid']) || (Sesion::existe() == false)) {
    
    
    MensajesFlash::anadirMensaje("error-login", "Debe tener una cuenta para poder reservar en nuestra pagina");

    header("Location: index.php");
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = ConexionDB::conectar();
    
    

    $uid = filter_var($_COOKIE['uid'], FILTER_SANITIZE_SPECIAL_CHARS);



    $usuDAO = new UsuarioDAO($conn);
    $usuario = $usuDAO->findByCookieId($uid);



    if (isset($_POST["confirmar"])) {
        $resDAO = new ReservaDAO($conn);

        
        $fecha = $_SESSION["fecha"];
        $horas = $_SESSION["horas"];
        $pistas = $_SESSION["pistas"];

        
        $reserva = new Reserva();
        $reserva->setId_usuario(Sesion::obtener());
        $reserva->setFecha($fecha->format("Y-m-d"));

        
        $resDAO->insert($reserva);
        $reserva->setHoras_reservadas($horas);
        $reserva->setPistas_reservadas($pistas);

        
        header("Location: misreservas.php");
        
        
    } elseif (isset($_POST["cancelar"])) {
        
        
        header("Location: reservas.php");
        
    } else {
        
        
        $fecha = $_POST["fecha"];
        $fecha = filter_var($fecha, FILTER_SANITIZE_SPECIAL_CHARS);

        $horas = $_POST["horas"];

        $pistas = $_POST["pistas"];

        $fechaActual = new DateTime(date("Y-m-d H:i:s"));
        $fechaReserva = new DateTime($fecha);

        
        if ($usuario->getFoto() != null) {
            
            
            $foto = $usuario->getFoto();
            
            
        } else {
            
            
            $foto = "default.png";
            
            
        }

        if ($fechaActual->format("Y-m-d") > $fechaReserva->format("Y-m-d")) {
            
            
            MensajesFlash::anadirMensaje("error-reserva", "No puedes reservar para un dia que ya ha pasado!!!");

            header("Location: reservas.php");
            
            
        } else {
            
            
            $_SESSION["fecha"] = $fechaReserva;
            $_SESSION["horas"] = $horas;
            $_SESSION["pistas"] = $pistas;
            
            
        }
        
    }
    
    
} else {
    
    
    MensajesFlash::anadirMensaje("error-login", "Debes tener una cuenta para poder reservar en nuestra pagina");

    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="es-ES">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Confirmacion</title>
        <script src="https://kit.fontawesome.com/e24647803f.js" crossorigin="anonymous"></script>
        <link href="css/estilos.css" rel="stylesheet">
        <link rel="shortcut icon" type="image/png" href="imagenes/favicon.png" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
        
    </head>
    
    
    <body>
    <nav style="color:white" class="navbar navbar-expand-lg navbar-dark bg-dark">

            <div id="foto-perfil" style="background-image: url('imagenes/fotosdeperfil/<?= $foto ?>'); margin-right: 15px;"></div>
                <h7 style="text-transform: uppercase;"><?= $usuario->getNombre(); ?></h7>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <ul style="margin-left:15px" class="navbar-nav mr-auto mt-2 mt-lg-0 d-flex flex-direction-column">
                    <li class="nav-item active">
                        <a class="enlace-nav" href="reservas.php">Realizar una nueva reserva</a>
                    </li>
                    <li class="nav-item">
                        <a class="enlace-nav" href="misreservas.php">Mis reservas</a>
                    </li>
                    <form hidden id="logout" action="logout.php"></form>
                    <?php if ($usuario->getRol() == "admin") : ?>
                        <a class="enlace-nav" href="admin/admin.php">Sistema de administracion</a>
                    <?php endif; ?>
                </ul>
                <button class="boton-logout" id="logoutboton" form="logout">Cerrar sesion</button>
            </div>
    </nav>

        
        
        <h1 class="titulo-reserva">Confirmación de reserva</h1>
        
        
      
        
        <form id="reserva" action="" method="post">
            
            
            <h5 class="texto-confirmacion">¿Desea confirmar una reserva para el día <strong><?= $fechaReserva->format("d/m/Y"); ?></strong>  a las <?php foreach ($horas as $hora): ?><strong><?= $hora ?></strong><?php endforeach; ?> <?php foreach ($pistas as $pista): ?> en la pista <strong><?= $pista?><?php endforeach; ?></strong>?</h5>

            
                <?= MensajesFlash::imprimirMensaje("error-fecha"); ?>
                
                

            <button class="botonconfirmar" type="submit" form="reserva" id="boton" name="confirmar">Confirmar reserva<i style="margin-left: 5px;" class="fa-solid fa-check"></i></button>
            <button class="botoncancelar" type="submit" form="reserva" id="boton" name="cancelar">Cancelar<i style="margin-left: 5px;" class="fa-solid fa-xmark"></i></button>
            
            
        </form>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
        <script type="text/javascript" src="js/jquery-3.6.0.min.js"></script>
    </body>
</html>
