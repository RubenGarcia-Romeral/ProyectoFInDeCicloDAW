<?php
class MensajesFlash {

    static public function anadirMensaje($tipo, $mensaje) {
        $_SESSION['mensajes_flash'][$tipo] = $mensaje;
    }

    static public function imprimirMensaje($tipo) {
        if (isset($_SESSION['mensajes_flash'][$tipo])) {
            print '<p class="error">' . $_SESSION['mensajes_flash'][$tipo] . '</p>';
            unset($_SESSION['mensajes_flash'][$tipo]);
        }
    }

}
