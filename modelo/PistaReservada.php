<?php

class PistaReservada {
    private $id;
    private $id_reserva;
    private $id_pista;
    
    public function getId() {
        return $this->id;
    }

    public function getId_reserva() {
        return $this->id_reserva;
    }

    public function getId_pista() {
        return $this->id_pista;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setId_reserva($id_reserva): void {
        $this->id_reserva = $id_reserva;
    }

    public function setId_pista($id_pista): void {
        $this->id_pista = $id_pista;
    }

}
