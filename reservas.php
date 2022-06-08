<?php
session_start();



require "modelo/ConexionDB.php";
require("modelo/Sesion.php");
require "modelo/Usuario.php";
require "modelo/UsuarioDAO.php";
require "modelo/MensajesFlash.php";



if (!isset($_COOKIE['uid']) || (Sesion::existe() == false)) {
    MensajesFlash::anadirMensaje("error-login", "Debe de iniciar sesion para poder hace una reserva");



    header("Location: index.php");
} else {


    $conn = ConexionDB::conectar();

    $usuDAO = new UsuarioDAO($conn);
    $usuario = $usuDAO->find(Sesion::obtener());


    $fechaActual = new DateTime(date("Y-m-d H:i:s"));


    if ($usuario->getFoto() != null) {
        $foto = $usuario->getFoto();
    } else {


        $foto = "default.png";
    }
}

$mes = date('m');
$dia = date('d');
$anio = date('Y');

$hoy = $anio . '-' . $mes . '-' . $dia;
?>

<!DOCTYPE html>
<html lang="es-ES">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
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
                <form hidden id="logout" action="logout.php"></form>
                <?php if ($usuario->getRol() == "admin") : ?>
                    <a class="enlace-nav" href="admin/admin.php">Sistema de administracion</a>
                <?php endif; ?>
            </ul>
            <button class="boton-logout" id="logoutboton" form="logout">Cerrar sesion</button>
        </div>
    </nav>




    <h1 class="titulo-reserva">Realizar una reserva</h1>


    <form id="reserva" action="confirmacion.php" method="post">
        <h6 class="subtitulo-reserva">Seleccione una fecha para su reserva: </h6>



        <input style="margin-bottom: 20px;" type="date" id="fecha" name="fecha" min="" value="<?php echo $hoy; ?>" <?= $fechaActual->format("Y-m-d") ?>">

        <h3 class="texto" style="display: none">Hora de la reserva:</h3>

        <div id="horas-container">

            <div id="horas" style="display: none">
                <div class="fila">
                    <div id="nueve" class="hora nueve" value="09:00">9:00</div>
                    <div id="once" class="hora" value="11:00">11:00</div>
                    <div id="trece" class="hora una" value="13:00">13:00</div>
                </div>
                <div class="fila">
                    <div id="diez" class="hora diez" value="10:00">10:00</div>
                    <div id="doce" class="hora" value="12:00">12:00</div>
                    <div id="catorce" class="hora dos" value="14:00">14:00</div>
                </div>
            </div>

        </div>

        <h3 class="texto" style="display: none">Pista de la reserva: </h3>
        <div id="pistas-container">
            <div id="pistas" style="display: none">
                <div class="fila">
                    <div id="pista-uno" class="pista uno" value="1">Pista 1</div>
                    <div id="pista-dos" class="pista pista-dos" value="2">Pista 2</div>
                </div>
                <div class="fila">
                    <div id="pista-tres" class="pista tres" value="3">Pista 3</div>
                    <div id="pista-cuatro" class="pista cuatro" value="4">Pista 4</div>
                </div>
            </div>
        </div>

        <button id="boton" type="submit" style="display: none;">Reservar</button>

        <div id="errores">

            <?= MensajesFlash::imprimirMensaje("error-reserva"); ?>
            <?= MensajesFlash::imprimirMensaje("error-login"); ?>
            <?= MensajesFlash::imprimirMensaje("error-fecha"); ?>



        </div>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="js/reservar.js"></script>

</body>

</html>