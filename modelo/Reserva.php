<?php
    
class Reserva {
    private $id;
    private $id_usuario;
    private $fecha;
    private $confirmada;
    private $usuario;
    private $horas_reservadas;
    private $pistas_reservadas;
    
    
    public function getId() {
        return $this->id;
    }
    

    public function getId_usuario() {
        return $this->id_usuario;
    }
    
    public function getFecha() {
        return $this->fecha;
    }
    

    public function getUsuario() {
        
        
        if (!isset($this->usuario)) {
            $usuarioDAO = new UsuarioDAO(ConexionDB::conectar());
            $this->usuario = $usuarioDAO->find($this->getId_usuario());
        }
        
        return $this->usuario;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setId_usuario($id_usuario): void {
        $this->id_usuario = $id_usuario;
    }

    public function setFecha($fecha): void {
        $this->fecha = $fecha;
    }

    public function setHora_inicio($hora_inicio): void {
        $this->hora_inicio = $hora_inicio;
    }

    public function setHora_fin($hora_fin): void {
        $this->hora_fin = $hora_fin;
    }

    public function setUsuario($usuario): void {
        $this->usuario = $usuario;
    }
    
    public function getConfirmada() {
        return $this->confirmada;
    }

    public function setConfirmada($confirmada): void {
        $this->confirmada = $confirmada;
    }
    
    public function getHoras_reservadas() {
        
        if (!isset($this->horas_reservadas)) {
            $horresDAO = new HoraReservadaDAO(ConexionDB::conectar());
            $this->horas_reservadas = $horresDAO->findByIdReserva($this->getId());
        }
        
        return $this->horas_reservadas;
    }

    public function setHoras_reservadas($horas_reservadas): void {
        
        $horresvDAO = new HoraReservadaDAO(ConexionDB::conectar());

        foreach ($horas_reservadas as $hora) {
            
            $hora = new DateTime(date($hora));
            
            $hora_reservada = new HoraReservada();
            
            $hora_reservada->setId_reserva($this->getId());
            
            
            $hora_reservada->setHora_inicio($hora->format("H:i:s"));
            
            $hora_reservada->setHora_fin(($hora->format("H") + 1) . $hora->format(":i:s"));

            $horresvDAO->insert($hora_reservada);
        }          
        
        $this->horas_reservadas = $horas_reservadas;
    }
    
    
    public function getPistas_reservadas() {
        
        if (!isset($this->pistas_reservadas)) {
            
            $pisresvDAO = new PistaReservadaDAO(ConexionDB::conectar());
            $this->pistas_reservadas = $pisresvDAO->findByIdReserva($this->getId());
            
        }
        
        return $this->pistas_reservadas;
    }

    
    public function setPistas_reservadas($pistas_reservadas): void {
        
        
        $pistresDAO = new PistaReservadaDAO(ConexionDB::conectar());

        foreach ($pistas_reservadas as $pista) {
            
            $pista_reservada = new PistaReservada();
            $pista_reservada->setId_reserva($this->getId());
            $pista_reservada->setId_pista($pista);
            
            
            $pistresDAO->insert($pista_reservada);
            
        }

        $this->pistas_reservadas = $pistas_reservadas;
    }

}
