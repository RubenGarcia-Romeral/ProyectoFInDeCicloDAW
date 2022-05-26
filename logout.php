<?php
    session_start();
    require ("modelo/Sesion.php");
    
    Sesion::cerrar();

    setcookie('uid', (time() - 5));
    header("Location: index.php");