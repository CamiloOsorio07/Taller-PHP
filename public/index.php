<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\MainController;

if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        [$k, $v] = array_map('trim', explode('=', $line, 2) + [null, null]);
        if ($k) putenv("$k=$v");
    }
}

$controller = new MainController();

$resultado = $_SESSION['resultado'] ?? null;
$message = $_SESSION['message'] ?? null;
$emailSent = $_SESSION['emailSent'] ?? null;
$mailerError = $_SESSION['mailer_error'] ?? null;

unset($_SESSION['resultado'], $_SESSION['message'], $_SESSION['emailSent'], $_SESSION['mailer_error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'calcular';

    if ($action === 'calcular') {
        $resultado = $controller->procesarFormulario($_POST);
        $_SESSION['resultado'] = $resultado;
        include __DIR__ . '/../app/Views/resultado.php';
    } elseif ($action === 'analizar_students') {
        $studentsResult = $controller->analizarEstudiantes($_POST);
        $_SESSION['resultado'] = $studentsResult;
        include __DIR__ . '/../app/Views/resultado.php';
    } elseif ($action === 'analizar_envios') {
        $enviosResult = $controller->analizarEnvios($_POST);
        $_SESSION['resultado'] = $enviosResult;
        include __DIR__ . '/../app/Views/resultado.php';
    } elseif ($action === 'pdf') {
        $html = $_POST['pdf_html'] ?? '<h1>Sin contenido</h1>';
        $controller->generarPdf($html);
    } elseif ($action === 'email') {
        $to = $_POST['email_to'] ?? null;
        $subject = $_POST['email_subject'] ?? 'Taller PHP - Resultados';
        $body = $_POST['email_body'] ?? '';
        
        $_SESSION['resultado'] = $resultado;
        
        $sent = $controller->enviarEmail($to, $subject, $body);
        
        if ($sent) {
            $_SESSION['emailSent'] = true;
            $_SESSION['message'] = 'Correo enviado correctamente.';
        } else {
            $_SESSION['emailSent'] = false;
            $_SESSION['message'] = 'No se pudo enviar el correo.';
        }
        
        header('Location: index.php');
        exit;
    }
} else {
    include __DIR__ . '/../app/Views/form.php';
}
