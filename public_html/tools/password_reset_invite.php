<?php
// Mostrar errores para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['account'])) {
    header('Location: /login.php');
    exit;
}
if ($_SESSION['account']!='popnoart') {
    echo 'No tienes permiso para acceder a esta página '.$_SESSION['account'];
    exit;
}

function generateToken($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['username'])) {
    $username = trim($_POST['username']);
    $resetFile = __DIR__ . '/../../storage/data/password_resets.json';
    $resets = file_exists($resetFile) ? json_decode(file_get_contents($resetFile), true) : [];
    $token = generateToken();
    $resets[$token] = [
        'username' => $username,
        'used' => false,
        'created_at' => date('c')
    ];
    file_put_contents($resetFile, json_encode($resets, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $resetLink = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . $token;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generar enlace de reseteo de contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-light py-5">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title mb-4 text-center"><i class="fas fa-key"></i> Generar enlace para resetear contraseña</h2>
                    <form method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Usuario a resetear contraseña</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning"><i class="fas fa-key"></i> Generar enlace</button>
                        </div>
                    </form>
                    <?php if (!empty($resetLink)): ?>
                        <div class="alert alert-success mt-4">
                            Enlace generado:<br>
                            <a href="<?= htmlspecialchars($resetLink) ?>" target="_blank"><?= htmlspecialchars($resetLink) ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
