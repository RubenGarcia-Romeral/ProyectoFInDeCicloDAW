<?php
    
class Usuario {
    private $id;
    private $nombre;
    private $apellidos;
    private $email;
    private $telefono;
    private $contrasena;
    private $foto;
    private $cookie_id;
    private $rol;
    private $reservas;
    
    private $usuario;
    
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getContrasena() {
        return $this->contrasena;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getFoto() {
        return $this->foto;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setNombre($nombre): void {
        $this->nombre = $nombre;
    }

    public function setContrasena($contrasena): void {
        $this->contrasena = $contrasena;
    }

    public function setEmail($email): void {
        $this->email = $email;
    }

    public function setFoto($foto): void {
        $this->foto = $foto;
    }
    

    function getReservas() {
        
        if (!isset($this->reservas)) {
            $resDAO = new ReservaDAO(ConexionDB::conectar());
            $this->reservas = $resDAO->findByIdUsuario($this->getId());
        }

        return $this->reservas;
    }
    
    function getUsuario() {
        
        if (!isset($this->usuario)) {
            $usuarioDAO = new UsuarioDAO(ConexionDB::conectar());
            $this->usuario = $usuarioDAO->find($this->getId());
        }
        
        return $this->usuario;
    }

    function getCookie_id() {
        return $this->cookie_id;
    }

    function setCookie_id($cookie_id) {
        $this->cookie_id = $cookie_id;
    }
    
    public function getApellidos() {
        return $this->apellidos;
    }

    public function setApellidos($apellidos): void {
        $this->apellidos = $apellidos;
    }
    
    public function getTelefono() {
        return $this->telefono;
    }

    public function setTelefono($telefono): void {
        $this->telefono = $telefono;
    }
    
    public function getRol() {
        return $this->rol;
    }

    public function setRol($rol): void {
        $this->rol = $rol;
    }

}
