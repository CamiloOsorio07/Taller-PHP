<?php

namespace App\Models;

class Calculadora {

    // Interés compuesto: capital * (1 + tasa)^tiempo
    public function interesCompuesto($capital, $tasa, $tiempo) {
        $capital = floatval($capital);
        $tasa = floatval($tasa);
        $tiempo = floatval($tiempo);
        return $capital * pow(1 + $tasa, $tiempo);
    }

    // Salario neto simple en Colombia (ejemplo: sólo salud y pensión empleados)
    public function salarioNeto($salario) {
        $salario = floatval($salario);
        $salud = $salario * 0.04; // 4%
        $pension = $salario * 0.04; // 4%
        return $salario - ($salud + $pension);
    }

    // Método adicional: conversión de km/h a m/s (ejemplo extra)
    public function kmhToMs($kmh) {
        return floatval($kmh) * (1000/3600);
    }
}
