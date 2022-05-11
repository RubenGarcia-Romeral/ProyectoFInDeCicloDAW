<?php

class ConexionDB {

    public static function conectar(): mysqli {
        
        $conn = new mysqli("localhost", "root", "", "reservapadel");
        if ($conn->connect_errno) {
            die("Error al conectar con MySQL: " . $conn->error);
        }
        
        return $conn;
    }
}
