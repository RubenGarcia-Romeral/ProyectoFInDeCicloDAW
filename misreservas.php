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
    MensajesFlash::anadirMensaje("error-login", "Tienes que iniciar sesion primero");


    header("Location: index.php");
} else {


    $conn = ConexionDB::conectar();

    $uid = filter_var($_COOKIE['uid'], FILTER_SANITIZE_SPECIAL_CHARS);

    $usuDAO = new UsuarioDAO($conn);
    $usuario = $usuDAO->findByCookieId($uid);
    $reservas = $usuario->getReservas();

    if ($usuario->getFoto() != null) {
        $foto = $usuario->getFoto();
    } else {

        $foto = "default.png";
    }
}
?>

<!DOCTYPE html>
<html lang="es-ES">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis reservas</title>
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
            <ul style="margin-left:15px" class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item active">
                    <a class="enlace-nav" href="reservas.php">Realizar una nueva reserva</a>
                </li>
                <li class="nav-item">
                    <a class="enlace-nav current" href="misreservas.php">Mis reservas</a>
                </li>
                <form hidden id="logout" action="logout.php"></form>
                <?php if ($usuario->getRol() == "admin") : ?>
                    <a class="enlace-nav" href="admin/admin.php">Sistema de administracion</a>
                <?php endif; ?>
            </ul>
            <button class="boton-logout" id="logoutboton" form="logout">Cerrar sesion</button>
        </div>
    </nav>


    <h1 class="titulo-reserva">Mis reservas</h1>


    <div class="row justify-content-evenly m-0 gap-2 p-2">
        <?php $fechaActual = new DateTime(date("Y-m-d H:i:s")); ?>


        <?php foreach ($reservas as $indice => $reserva) : ?>
            <?php

            $horas = $reserva->getHoras_reservadas();
            $pistas = $reserva->getPistas_reservadas();

            $fechaReserva = new DateTime(date($reserva->getFecha() . " 00:00:00"));
            $diferenciaEnDias = $fechaReserva->diff($fechaActual)->format("%d");
            ?>


            <div class="card col-12 col-md-5 col-xl-3 p-0 mb-4">
                <img class="card-img-top" src="imagenes/descargar.jpg" alt="Imagen carta">
                <div class="card-body">
                    <h5 class="card-title">Fecha de la reserva: <strong><?= $fechaReserva->format("d/m/Y"); ?></strong></h5>
                    <p class="card-text">Reserva <?= ($indice + 1) ?></p>
                </div>
                <ul class="list-group list-group-flush">
                    <li style="background-color: #BCE5F5;" class="list-group-item">Pistas:
                        <?php foreach ($pistas as $pista) : ?>
                            <strong>Pista <?= $pista->getId_pista() ?></strong>
                        <?php endforeach; ?>
                    </li>
                    <li style="background-color: #B2EDAF;" class="list-group-item">Horas: de
                        <?php foreach ($horas as $hora) : ?>
                            <strong><?= $hora->getHora_inicio() ?></strong> a <strong><?= $hora->getHora_fin() ?></strong>
                        <?php endforeach; ?>
                    </li>
                </ul>
                <?php if ($diferenciaEnDias >= 1) : ?>
                    <form method="post" action="cancelar_reserva.php" id="cancelar">
                        <input type="hidden" name="t" value="<?= $_SESSION["token"] ?>">
                        <input type="hidden" name="id" value="<?= $reserva->getId(); ?>">
                    </form>
                    <div class="card-body">
                        <button id="botonborrar" form="cancelar"><i id="borrarbasura" class="fas fa-trash fa-2x fa-spin basura" title="Cancelar reserva"></i></button>
                    </div>
                <?php endif; ?>
            </div>



        <?php endforeach; ?>
    </div>


    <?= MensajesFlash::imprimirMensaje("error-token") ?>
    <?= MensajesFlash::imprimirMensaje("error-cancelado") ?>

              
    <script type="text/javascript" src="js/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
</body>

</html>