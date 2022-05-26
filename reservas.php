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
?>

<!DOCTYPE html>
<html lang="es-ES">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title></title>
        <link href="css/estilos.css" rel="stylesheet">
    </head>
    <body>
        <nav>

            <div id="foto-perfil" style="background-image: url('imagenes/fotosdeperfil/<?= $foto ?>')"></div> <?= $usuario->getNombre(); ?> |  <button id="logoutboton" form="logout">Cerrar sesion</button>|    
            <a href="reservas.php">  Realizar una nueva reserva</a>
            <a href="misreservas.php">Mis reservas</a>
            <form id="logout" action="logout.php"></form>
<!--            <img class="fotoraqueta" src="imagenes/raqueta.png" alt=""/>-->
            <?php if ($usuario->getRol() == "admin"): ?>
            <a href="admin/admin.php">Sistema de administracion</a>
            <?php            endif; ?>



        </nav>
        <br><br>
        <h1>Realizar una reserva</h1>
        <br><br><br>

        <form id="reserva" action="confirmacion.php" method="post">
            Seleccione una fecha para su reserva: 


            <br><br><br>
            <input type="date" id="fecha" name="fecha" min="" value="Introduza la fecha de reserva"<?= $fechaActual->format("Y-m-d") ?>"><br><br>

            <h3 class="texto" style="display: none">Elija la hora u horas que quiera estar:</h3>

            <div id="horas-container">

                <div class="divimagen">

                    <table id="horas" style="display: none">
                        <tr class="fila">
                            <td id="nueve" class="hora" value="09:00">9:00</td>
                            <td id="once" class="hora" value="11:00">11:00</td>
                            <td id="trece" class="hora" value="13:00">13:00</td>
                        </tr>
                        <tr class="fila">
                            <td id="diez" class="hora" value="10:00">10:00</td>
                            <td id="doce" class="hora" value="12:00">12:00</td>
                            <td id="catorce" class="hora" value="14:00">14:00</td>
                        </tr>
                    </table>
                </div>
            </div>

            <h3 class="texto" style="display: none">Seleccione la pista o postas donde quiera jugar:</h3>
            <div id="pistas-container">
                <table id="pistas" style="display: none">
                    <tr class="fila">
                        <td id="pista-uno" class="pista" value="1">Pista 1</td>
                        <td id="pista-dos" class="pista" value="2">Pista 2</td>
                    </tr>
                    <tr class="fila">
                        <td id="pista-tres" class="pista" value="3">Pista 3</td>
                        <td id="pista-cuatro" class="pista" value="4">Pista 4</td>
                    </tr>
                </table>
            </div>

            <button id="boton" type="submit" style="display: none;">Reservar</button>

            <div id="errores">

                <?= MensajesFlash::imprimirMensaje("error-reserva"); ?>
                <?= MensajesFlash::imprimirMensaje("error-login"); ?>
                <?= MensajesFlash::imprimirMensaje("error-fecha"); ?>



            </div>
        </form>
        <script type="text/javascript" src="js/jquery-3.6.0.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                
                $("#fecha").change(function (e) {
                    let reservas = new Array();
                    let pistas = new Array();


                    $("#errores").empty();
                    
                    $(".texto, #horas, #pistas").css("display", "flex");



                    $("#nueve, #diez, #once, #doce, #trece, #catorce, #pista-uno, #pista-dos, #pista-tres, #pista-cuatro").removeClass("no-disponible");
                    $("#nueve, #diez, #once, #doce, #trece, #catorce, #pista-uno, #pista-dos, #pista-tres, #pista-cuatro").removeClass("seleccionado");
                    $("#nueve, #diez, #once, #doce, #trece, #catorce, #pista-uno, #pista-dos, #pista-tres, #pista-cuatro").addClass("disponible");

                    $("#nueve, #diez, #once, #doce, #trece, #catorce").on("click", function () {
                        
                        
                        if (reservas.includes($(this).attr("value"))) {
                            
                            let indice = reservas.indexOf($(this).attr("value"));
                            if (indice > -1) {
                                
                                $(this).removeClass("seleccionado");
                                reservas.splice(indice, 1);


                                if (reservas.length === 0) {
                                    $("#boton").css("display", "none");
                                }
                                
                            }
                            
                        } else {
                            
                            $(this).addClass("seleccionado");
                            reservas.push($(this).attr("value"));
                            reservas.sort();

                            if (reservas.length > 0 && pistas.length > 0) {
                                $("#boton").css("display", "inline-block");
                                
                            }
                            
                        }
                    });



                    $("#pista-uno, #pista-dos, #pista-tres, #pista-cuatro").on("click", function () {
                        if (pistas.includes($(this).attr("value"))) {
                            let indice = pistas.indexOf($(this).attr("value"));
                            if (indice > -1) {
                                $(this).removeClass("seleccionado");
                                pistas.splice(indice, 1);

                                if (pistas.length === 0) {
                                    $("#boton").css("display", "none");
                                }
                                
                            }
                            
                            
                        } else {
                            
                            
                            $(this).addClass("seleccionado");
                            pistas.push($(this).attr("value"));
                            pistas.sort();

                            if (pistas.length > 0 && reservas.length > 0) {
                                $("#boton").css("display", "inline-block");
                                
                            }
                            
                        }
                        
                        
                    });

                    $("#reserva").submit(function (e) {
                        
                        
                        for (let j = 0; j < pistas.length; j++) {
                            
                            
                            $("#reserva").append("<input type='text' name='pistas[]' " +
                                    
                                    "value='" + pistas[j] + "'  style='display: none'>");
                        }

                        for (let i = 0; i < reservas.length; i++) {
                            $("#reserva").append("<input type='text' name='horas[]' " +
                                    "value='" + reservas[i] + "' style='display: none'>");
                            
                        }
                    });

                    $.ajax({
                    
                    
                        url: "ajax/fetch_reservas.php",
                        
                        type: "post",
                        
                        
                        data: {fecha: $("#fecha").val()},
                        
                        
                        success: function (response) {
                            
                            
                            const reservas = JSON.parse(response);
                            reservas.forEach(reserva => {
                                
                                
                                $(`#${reserva.hora}`).removeClass("disponible");
                                $(`#${reserva.hora}`).addClass("no-disponible");
                                $(`#${reserva.hora}`).off("click");

                                $(`#${reserva.pista}`).removeClass("disponible");
                                $(`#${reserva.pista}`).addClass("no-disponible");
                                $(`#${reserva.pista}`).off("click");
                                
                                
                            });
                            
                            
                        }
                        
                        
                        
                    });
                });
                
            });
        </script>

    </body>
</html>