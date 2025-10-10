<?php
// register.php
// Página de registro de usuario a partir de un enlace de invitación

if (empty($_GET['token'])) {
    die('Invitación no válida.');
}
$token = $_GET['token'];
$invitesFile = __DIR__ . '/../storage/data/invites.json';
$authFile = __DIR__ . '/../storage/data/auth.json';

$invites = file_exists($invitesFile) ? json_decode(file_get_contents($invitesFile), true) : [];
if (!isset($invites[$token]) || !is_array($invites[$token]) || !empty($invites[$token]['used'])) {
    die('Invitación no válida o ya usada.');
}
$username = $invites[$token]['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['password'])) {
    $password = $_POST['password'];
    $hash = password_hash($password, PASSWORD_DEFAULT);
    // Añadir usuario a auth.json
    $auth = file_exists($authFile) ? json_decode(file_get_contents($authFile), true) : [];
    // Evitar duplicados
    foreach ($auth as $user) {
        if ($user['username'] === $username) {
            $error = 'El usuario ya existe.';
            break;
        }
    }
    if (empty($error)) {
        $auth[] = [
            'username' => $username,
            'password_hash' => $hash
        ];
        file_put_contents($authFile, json_encode($auth, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        // Marcar invitación como usada
        $invites[$token]['used'] = true;
        file_put_contents($invitesFile, json_encode($invites, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title mb-4 text-center">Registro de usuario</h2>
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success">Usuario registrado correctamente. Ya puedes iniciar sesión.</div>
                    <?php else: ?>
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Usuario</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($username) ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Elige tu contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Registrar</button>
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
