<?php
define('ROOT', dirname(__DIR__));

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_GET['token'])) {
    die('Token no proporcionado.');
}
$token = $_GET['token'];
$resetFile = ROOT.'/storage/data/password_resets.json';
$resets = file_exists($resetFile) ? json_decode(file_get_contents($resetFile), true) : [];

if (!isset($resets[$token])) {
    die('Enlace inválido o expirado.');
}
if ($resets[$token]['used']) {
    die('Este enlace ya ha sido utilizado.');
}
$username = $resets[$token]['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['username']) && !empty($_POST['new_password'])) {
    if ($_POST['username'] !== $username) {
        $error = 'El usuario no coincide con el autorizado para este enlace.';
    } else {
        // Actualizar la contraseña en auth.json
        $authFile = ROOT.'/storage/data/auth.json';
        $auth = file_exists($authFile) ? json_decode(file_get_contents($authFile), true) : [];
        $found = false;
        foreach ($auth as &$user) {
            if ($user['username'] === $username) {
                $user['password_hash'] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                $found = true;
                break;
            }
        }
        unset($user);
        if ($found) {
            file_put_contents($authFile, json_encode($auth, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $resets[$token]['used'] = true;
            file_put_contents($resetFile, json_encode($resets, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $success = 'Contraseña actualizada correctamente para el usuario ' . htmlspecialchars($username);
        } else {
            $error = 'Usuario no encontrado en el sistema.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resetear contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-light py-5">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title mb-4 text-center"><i class="fas fa-key"></i> Resetear contraseña</h2>
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php else: ?>
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                                <small class="form-text text-muted">Solo el usuario autorizado puede usar este enlace.</small>
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Nueva contraseña</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-warning"><i class="fas fa-key"></i> Cambiar contraseña</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
