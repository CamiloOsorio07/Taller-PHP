<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Taller PHP - Calculadora</title>
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
        
        .lime-text {
            color: var(--lime-primary) !important;
        }
        
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
        
        .card {
            background-color: var(--bg-card);
            border: 1px solid #30363d;
        }
        
        .card h5 {
            color: var(--lime-primary);
        }
        
        .form-control {
            background-color: #21262d;
            border-color: #30363d;
            color: var(--text-light);
        }
        
        .form-control:focus {
            background-color: #21262d;
            border-color: var(--lime-primary);
            color: var(--text-light);
            box-shadow: 0 0 0 0.25rem rgba(50, 205, 50, 0.25);
        }
        
        .form-control::placeholder {
            color: #6e7681;
        }
        
        label {
            color: var(--lime-light);
        }
        
        .footer {
            color: #6e7681;
            border-top: 1px solid #30363d;
        }
        
        h1 {
            color: var(--lime-light);
            text-shadow: 0 0 10px rgba(124, 252, 0, 0.3);
        }
        
        code {
            color: var(--lime-primary);
            background-color: #21262d;
        }
    </style>
</head>
<body class="container py-4">
    <h1 class="mb-4 text-center">Taller PHP</h1>

    <div class="row">
        <div class="col-md-6">
            <div class="card p-3 mb-3">
                <h5>Calculadora financiera</h5>
                <form method="POST" action="index.php">
                    <input type="hidden" name="action" value="calcular">
                    <div class="mb-2">
                        <label>Capital inicial</label>
                        <input class="form-control" name="capital" type="number" step="0.01" placeholder="1000000" required>
                    </div>
                    <div class="mb-2">
                        <label>Tasa de interés</label>
                        <input class="form-control" name="tasa" type="number" step="0.0001" placeholder="0.05" required>
                    </div>
                    <div class="mb-2">
                        <label>Períodos</label>
                        <input class="form-control" name="tiempo" type="number" placeholder="12" required>
                    </div>
                    <div class="mb-2">
                        <label>Salario mensual</label>
                        <input class="form-control" name="salario" type="number" step="0.01" placeholder="1500000" required>
                    </div>
                    <button class="btn btn-lime w-100">Calcular</button>
                </form>
            </div>

            <div class="card p-3">
                <h5>Análisis de datos</h5>
                <form method="POST" action="index.php">
                    <input type="hidden" name="action" value="calcular">
                    <p class="small text-muted">Los datos de ejemplo se usarán para el análisis.</p>
                    <button class="btn btn-outline-lime w-100">Ejecutar análisis</button>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-3">
                <h5>Exportar resultados</h5>
                <p class="small text-muted">Desde la vista de resultados puedes generar PDF o enviar correo.</p>
                <p class="small text-muted">Configura el correo en el archivo <code>.env</code> con la variable <code>MAILER_DSN</code>.</p>
            </div>
        </div>
    </div>

    <footer class="mt-4 text-center footer py-3">Taller PHP - Proyecto MVC</footer>
</body>
</html>
