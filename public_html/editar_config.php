<?php
session_start();
if (!isset($_SESSION['account'])) {
    die('No autenticado');
}

$username = $_SESSION['account'];
$configPath = __DIR__ . "/../storage/data/accounts/$username/config.json";

// Leer el archivo actual
$config = [
    'units_status' => [],
    'resources_status' => [],
    'tasks_status' => [],
    'tests_status' => [],
    'notes_status' => [],
    'col1' => [],
    'col2' => [],
    'col3' => []
];
if (file_exists($configPath)) {
    $json = file_get_contents($configPath);
    $data = json_decode($json, true);
    if (is_array($data)) {
        $config = array_merge($config, $data);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($config as $key => $value) {
        if (isset($_POST[$key])) {
            $config[$key] = array_map('trim', explode(',', $_POST[$key]));
        }
    }
    file_put_contents($configPath, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $msg = '¡Configuración actualizada!';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Configuración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title mb-4 text-center">Editar Configuración de Estados</h2>
                    <?php if (!empty($msg)): ?>
                        <div class="alert alert-success"> <?= htmlspecialchars($msg) ?> </div>
                    <?php endif; ?>
                    <form method="post">
                        <?php foreach ($config as $key => $value): ?>
                            <div class="mb-3">
                                <label class="form-label" for="<?= $key ?>">
                                    <?= ucfirst(str_replace('_', ' ', $key)) ?>
                                </label>
                                <input type="text" class="form-control" id="<?= $key ?>" name="<?= $key ?>" value="<?= htmlspecialchars(implode(',', $value)) ?>">
                            </div>
                        <?php endforeach; ?>
                        <div class="form-text">Separa por coma.</div>
                        <div class="form-text">Para las columnas (Colx) tienes disponible; pending_tasks, units, resources, tests, notes, calendar, done_tasks</div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
            <p class="text-center text-muted mt-3 small"><a href="/index.php">&larr; Volver</a></p>
        </div>
    </div>
</div>
</body>
</html>
