<?php

namespace App\Models;

class Envio {
    public $id;
    public $ciudad;
    public $transportista;
    public $peso;
    public $costoKilo;
    public $estado;

    public function __construct($id, $ciudad, $transportista, $peso, $costoKilo, $estado) {
        $this->id = $id;
        $this->ciudad = $ciudad;
        $this->transportista = $transportista;
        $this->peso = floatval($peso);
        $this->costoKilo = floatval($costoKilo);
        $this->estado = $estado;
    }

    public function costoTotal() {
        return $this->peso * $this->costoKilo;
    }
}
