<?php
class HoraReservada {
    private $id;
    private $id_reserva;
    private $hora_inicio;
    private $hora_fin;
    
    public function getId() {
        return $this->id;
    }

    public function getId_reserva() {
        return $this->id_reserva;
    }

    public function getHora_inicio() {
        return $this->hora_inicio;
    }

    public function getHora_fin() {
        return $this->hora_fin;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setId_reserva($id_reserva): void {
        $this->id_reserva = $id_reserva;
    }

    public function setHora_inicio($hora_inicio): void {
        $this->hora_inicio = $hora_inicio;
    }

    public function setHora_fin($hora_fin): void {
        $this->hora_fin = $hora_fin;
    }

}
