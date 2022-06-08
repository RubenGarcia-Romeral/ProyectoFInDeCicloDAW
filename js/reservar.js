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