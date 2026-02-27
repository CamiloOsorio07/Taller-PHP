<?php

namespace App\Models;

class Estudiante {
    public $nombre;
    public $calificacion;
    public $carrera;

    public function __construct($nombre, $calificacion, $carrera) {
        $this->nombre = $nombre;
        $this->calificacion = floatval($calificacion);
        $this->carrera = $carrera;
    }
}
