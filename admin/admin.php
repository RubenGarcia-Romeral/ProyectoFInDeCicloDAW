<?php
    session_start();
    

    require("../modelo/ConexionDB.php");
    require("../modelo/Sesion.php");
    require("../modelo/Usuario.php");
    require("../modelo/UsuarioDAO.php");
    require("../modelo/ReservaDAO.php");
    require("../modelo/HoraReservadaDAO.php");
    require("../modelo/PistaReservadaDAO.php");
    require("../modelo/MensajesFlash.php");
    
    
    if (!isset($_COOKIE['uid']) || (Sesion::existe() == false)) {
        MensajesFlash::anadirMensaje("error-login", "Debe de iniciar sesion en nuestra pagina");

        header("Location: index.php");
        
    } else {
        
        $conn = ConexionDB::conectar();
        
        
        
        $uid = filter_var($_COOKIE['uid'], FILTER_SANITIZE_SPECIAL_CHARS);
        
        $usuDAO = new UsuarioDAO($conn);
        $usuario = $usuDAO->findByCookieId($uid);
        
        if ($usuario->getRol() == "admin") {
            
            $resDAO = new ReservaDAO($conn);
            $reservas = $resDAO->findAll("fecha", "asc");

            if ($usuario->getFoto() != null) {
                
                $foto = $usuario->getFoto();
                
            } else {
                
                $foto = "default.png";
                
            }
        } else {
            
            MensajesFlash::anadirMensaje("error-login", "No puedes acceder");
            
            header("Location: index.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="es-ES">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administracion</title>
    <link href="css/estilos.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/dc32817a4f.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="shortcut icon" type="image/png" href="imagenes/favicon.png" />
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
            <a class="enlace-nav current" href="reservas.php">Realizar una nueva reserva</a>
        </li>
        <li class="nav-item">
            <a class="enlace-nav" href="misreservas.php">Mis reservas</a>
        </li>
    </ul>
    <button class="boton-logout" id="logoutboton" form="logout">Cerrar sesion</button>
</div>
</nav>

    <h1>Reservas</h1>

    <div id="reservas">
        <?php $fechaActual  = new DateTime(date("Y-m-d H:i:s")); ?>
        
        
        <?php foreach ($reservas as $indice=>$reserva): ?>
        
            <?php
            
                $usuarioReserva = $reserva->getUsuario();

                $horas = $reserva->getHoras_reservadas();
                $pistas = $reserva->getPistas_reservadas();
            ?>
        
            <div class="reserva">
                <h3>
                    
                    Reserva nº <?= ($indice + 1) ?> (id: <?= $reserva->getId() ?>)
                        <?php if ($reserva->getConfirmada() == "NO"): ?>
                            <a href="confirmar_reserva.php?id=<?= $reserva->getId(); ?>"></a>
                        <?php endif; ?>
                        <a href="cancelar_reserva.php?id=<?= $reserva->getId(); ?>"></a>
                </h3>
                
                Usuario: id nº <?= $usuarioReserva->getId(); ?>, nombre: <?= $usuarioReserva->getNombre(); ?> <?= $usuarioReserva->getApellidos(); ?><br>
                Fecha: <?= $reserva->getFecha(); ?><br>
                
                
            </div>
        
        <?php endforeach; ?>
        
    </div>
    
    <script type="text/javascript" src="../js/jquery-3.6.0.min.js"></script>
</body>
</html>
