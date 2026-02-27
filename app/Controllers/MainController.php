<?php

namespace App\Controllers;

use App\Models\Estudiante;
use App\Models\Envio;
use App\Models\Calculadora;
use Dompdf\Dompdf;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

class MainController {

    public function procesarFormulario($data) {
        $calculadora = new Calculadora();

        $capital = isset($data['capital']) ? floatval($data['capital']) : 0;
        $tasa = isset($data['tasa']) ? floatval($data['tasa']) : 0;
        $tiempo = isset($data['tiempo']) ? floatval($data['tiempo']) : 0;
        $salario = isset($data['salario']) ? floatval($data['salario']) : 0;

        $interes = $calculadora->interesCompuesto($capital, $tasa, $tiempo);
        $neto = $calculadora->salarioNeto($salario);

        $estudiantesRaw = $this->datosEjemploEstudiantes();
        $enviosRaw = $this->datosEjemploEnvios();

        $studentsAnalysis = $this->analizarEstudiantesArray($estudiantesRaw);
        $enviosAnalysis = $this->analizarEnviosArray($enviosRaw);

        return [
            'interes' => $interes,
            'salarioNeto' => $neto,
            'studentsAnalysis' => $studentsAnalysis,
            'enviosAnalysis' => $enviosAnalysis,
            'estudiantesRaw' => $estudiantesRaw,
            'enviosRaw' => $enviosRaw
        ];
    }

    private function datosEjemploEstudiantes() {
        return [
            ['nombre' => 'Ana', 'calificacion' => 4.2, 'carrera' => 'Ingenieria'],
            ['nombre' => 'Luis', 'calificacion' => 3.8, 'carrera' => 'Medicina'],
            ['nombre' => 'Carla', 'calificacion' => 4.6, 'carrera' => 'Ingenieria'],
            ['nombre' => 'Juan', 'calificacion' => 2.9, 'carrera' => 'Derecho'],
            ['nombre' => 'María', 'calificacion' => 3.5, 'carrera' => 'Medicina'],
            ['nombre' => 'Pedro', 'calificacion' => 4.9, 'carrera' => 'Derecho']
        ];
    }

    private function datosEjemploEnvios() {
        return [
            ['id' => 1, 'ciudad' => 'Bogota', 'transportista' => 'TransA', 'peso' => 10, 'costoKilo' => 5, 'estado' => 'Entregado'],
            ['id' => 2, 'ciudad' => 'Medellin', 'transportista' => 'TransB', 'peso' => 20, 'costoKilo' => 4.5, 'estado' => 'Entregado'],
            ['id' => 3, 'ciudad' => 'Cali', 'transportista' => 'TransA', 'peso' => 5, 'costoKilo' => 6, 'estado' => 'Cancelado'],
            ['id' => 4, 'ciudad' => 'Bogota', 'transportista' => 'TransC', 'peso' => 7, 'costoKilo' => 5.5, 'estado' => 'Entregado'],
            ['id' => 5, 'ciudad' => 'Medellin', 'transportista' => 'TransB', 'peso' => 12, 'costoKilo' => 4.5, 'estado' => 'En ruta']
        ];
    }

    public function analizarEstudiantesArray(array $estudiantes) {
        $suma = [];
        $conteo = [];
        foreach ($estudiantes as $e) {
            $c = $e['carrera'];
            $suma[$c] = ($suma[$c] ?? 0) + $e['calificacion'];
            $conteo[$c] = ($conteo[$c] ?? 0) + 1;
        }
        $promedios = [];
        foreach ($suma as $c => $total) {
            $promedios[$c] = $total / $conteo[$c];
        }

        $carreraMasBaja = null;
        $minProm = INF;
        foreach ($promedios as $c => $p) {
            if ($p < $minProm) {
                $minProm = $p;
                $carreraMasBaja = $c;
            }
        }

        $porEncima = [];
        foreach ($estudiantes as $e) {
            if ($e['calificacion'] > $promedios[$e['carrera']]) {
                $porEncima[] = $e['nombre'];
            }
        }

        return [
            'promedios' => $promedios,
            'carreraMasBaja' => $carreraMasBaja,
            'estudiantesPorEncima' => $porEncima
        ];
    }

    public function analizarEnviosArray(array $envios) {
        $costoTotalEntregados = 0;
        $pesoPorCiudad = [];
        $entregasPorTransportista = [];

        foreach ($envios as $e) {
            if ($e['estado'] === 'Entregado') {
                $costoTotalEntregados += $e['peso'] * $e['costoKilo'];
                $entregasPorTransportista[$e['transportista']] = ($entregasPorTransportista[$e['transportista']] ?? 0) + 1;
            }
            $pesoPorCiudad[$e['ciudad']] = ($pesoPorCiudad[$e['ciudad']] ?? 0) + $e['peso'];
        }

        $ciudadMayorPeso = null;
        $maxPeso = -INF;
        foreach ($pesoPorCiudad as $ciudad => $peso) {
            if ($peso > $maxPeso) { $maxPeso = $peso; $ciudadMayorPeso = $ciudad; }
        }

        $topTransportista = null;
        $maxEnt = -INF;
        foreach ($entregasPorTransportista as $t => $cnt) {
            if ($cnt > $maxEnt) { $maxEnt = $cnt; $topTransportista = $t; }
        }

        return [
            'costoTotalEntregados' => $costoTotalEntregados,
            'ciudadMayorPeso' => $ciudadMayorPeso,
            'topTransportista' => $topTransportista
        ];
    }

    public function generarPdf($html) {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('resultado_taller.pdf', ['Attachment' => false]);
        exit;
    }

    public function enviarEmail($to, $subject, $body) {
        $dsn = getenv('MAILER_DSN') 
            ?? ($_ENV['MAILER_DSN'] ?? null)
            ?? ($_SERVER['MAILER_DSN'] ?? null);
            
        $fromEmail = getenv('MAIL_FROM') 
            ?? ($_ENV['MAIL_FROM'] ?? null)
            ?? ($_SERVER['MAIL_FROM'] ?? 'no-reply@example.com');
        
        if (!$dsn) {
            $_SESSION['mailer_error'] = 'Configuración SMTP no encontrada. Edita el archivo .env';
            return false;
        }

        try {
            $transport = Transport::fromDsn($dsn);
            $mailer = new Mailer($transport);

            $email = (new Email())
                ->from($fromEmail)
                ->to($to)
                ->subject($subject)
                ->html($body);

            $mailer->send($email);
            return true;
        } catch (\Throwable $ex) {
            $_SESSION['mailer_error'] = $ex->getMessage();
            error_log('Error envío email: ' . $ex->getMessage());
            return false;
        }
    }
}
