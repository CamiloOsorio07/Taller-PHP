<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resultados - Taller PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --lime-primary: #32CD32;
            --lime-light: #7CFC00;
            --lime-dark: #228B22;
            --bg-dark: #0d1117;
            --bg-card: #161b22;
            --text-light: #c9d1d9;
        }
        
        body {
            background-color: var(--bg-dark);
            color: var(--text-light);
            min-height: 100vh;
        }
        
        .lime-text { color: var(--lime-primary) !important; }
        
        .btn-lime {
            background: linear-gradient(135deg, var(--lime-primary), var(--lime-dark));
            border: none;
            color: #000;
            font-weight: 600;
        }
        
        .btn-lime:hover {
            background: linear-gradient(135deg, var(--lime-light), var(--lime-primary));
            color: #000;
        }
        
        .btn-outline-lime {
            border-color: var(--lime-primary);
            color: var(--lime-primary);
        }
        
        .btn-outline-lime:hover {
            background-color: var(--lime-primary);
            color: #000;
        }
        
        .btn-secondary {
            background-color: #30363d;
            border-color: #30363d;
        }
        
        .card {
            background-color: var(--bg-card);
            border: 1px solid #30363d;
        }
        
        .card h5 { color: var(--lime-primary); }
        
        .form-control {
            background-color: #21262d;
            border-color: #30363d;
            color: var(--text-light);
        }
        
        h1, h2, h3 { color: var(--lime-light); }
        strong { color: var(--lime-primary); }
        
        .alert-success {
            background-color: rgba(50, 205, 50, 0.2);
            border-color: var(--lime-primary);
            color: var(--lime-light);
        }
        
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.2);
            border-color: #dc3545;
            color: #f8d7da;
        }
    </style>
</head>
<body class="container py-4">
    <h1 class="mb-4 text-center">Resultados</h1>

    <?php if (isset($message)): ?>
        <div class="alert alert-<?= $emailSent ? 'success' : 'danger' ?> mb-3">
            <?= htmlspecialchars($message) ?>
            <?php if (isset($mailerError)): ?>
                <br><small><?= htmlspecialchars($mailerError) ?></small>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($resultado)): ?>
        <div class="card mb-3 p-3">
            <h5>Interés compuesto</h5>
            <p>Valor final: <strong><?= number_format($resultado['interes'], 2, ',', '.') ?></strong></p>
            <h5>Salario neto</h5>
            <p>Salario neto: <strong><?= number_format($resultado['salarioNeto'], 2, ',', '.') ?></strong></p>
        </div>

        <div class="card mb-3 p-3">
            <h5>Análisis de Estudiantes</h5>
            <?php $sa = $resultado['studentsAnalysis']; ?>
            <p><strong>Promedios por carrera:</strong></p>
            <ul>
                <?php foreach ($sa['promedios'] as $c => $p): ?>
                    <li><?= htmlspecialchars($c) ?>: <?= number_format($p, 2, ',', '.') ?></li>
                <?php endforeach; ?>
            </ul>
            <p><strong>Carrera con promedio más bajo:</strong> <?= htmlspecialchars($sa['carreraMasBaja']) ?></p>
            <p><strong>Estudiantes sobre el promedio:</strong> <?= implode(', ', $sa['estudiantesPorEncima']) ?></p>
        </div>

        <div class="card mb-3 p-3">
            <h5>Análisis de Envíos</h5>
            <?php $ea = $resultado['enviosAnalysis']; ?>
            <p>Costo total entregados: <strong><?= number_format($ea['costoTotalEntregados'], 2, ',', '.') ?></strong></p>
            <p>Ciudad con mayor peso total: <strong><?= htmlspecialchars($ea['ciudadMayorPeso']) ?></strong></p>
            <p>Transportista con más entregas: <strong><?= htmlspecialchars($ea['topTransportista']) ?></strong></p>
        </div>

        <div class="card p-3 mb-3">
            <h5>Exportar resultados</h5>

            <?php
                ob_start();
            ?>
            <h1>Resultados Taller PHP</h1>
            <h2>Interés compuesto</h2>
            <p>Valor final: <?= number_format($resultado['interes'], 2, ',', '.') ?></p>
            <h2>Salario neto</h2>
            <p><?= number_format($resultado['salarioNeto'], 2, ',', '.') ?></p>
            <h2>Resumen estudiantes</h2>
            <ul>
            <?php foreach ($sa['promedios'] as $c => $p): ?>
                <li><?= htmlspecialchars($c) ?>: <?= number_format($p, 2, ',', '.') ?></li>
            <?php endforeach; ?>
            </ul>
            <?php $pdf_html = ob_get_clean(); ?>

            <form method="POST" style="display:inline-block;">
                <input type="hidden" name="action" value="pdf">
                <input type="hidden" name="pdf_html" value="<?= htmlspecialchars($pdf_html) ?>">
                <button class="btn btn-outline-lime">Generar PDF</button>
            </form>

            <form method="POST" style="display:inline-block; margin-left:8px;">
                <input type="hidden" name="action" value="email">
                <div class="mb-2">
                    <input class="form-control" name="email_to" placeholder="destinatario@correo.com" required>
                </div>
                <input type="hidden" name="email_subject" value="Resultados Taller PHP">
                <input type="hidden" name="email_body" value="<?= htmlspecialchars($pdf_html) ?>">
                <button class="btn btn-outline-lime">Enviar correo</button>
            </form>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No hay resultados. Usa el formulario para calcular.</div>
    <?php endif; ?>

    <a href="index.php" class="btn btn-secondary">Volver</a>

</body>
</html>
