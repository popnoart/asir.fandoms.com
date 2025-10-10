<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (isset($_SESSION['account'])) {
    header('Location: index.php');
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authFile = __DIR__ . '/../storage/data/auth.json';
    $data = file_get_contents($authFile);
    $auth = json_decode($data, true);
    $user = array_filter($auth, fn($item) => $item['username'] === $_POST['username']);
    if ($user && password_verify($_POST['password'], $user[0]['password_hash'])) {
        $_SESSION['account'] = $user[0]['username'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Usuario o contraseña incorrectos';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-8 col-md-5">
                <div class="card shadow-sm mt-5">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Iniciar sesión</h2>
                        <form method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Usuario" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                            </div>
                            <?php if ($error): ?>
                                <div class="alert alert-danger py-2"><?= htmlspecialchars($error); ?></div>
                            <?php endif; ?>
                            <div class="d-grid">
                                <button type="submit" name="Login"class="btn btn-primary">Entrar</button>
                            </div>
                        </form>
                    </div>
                </div>
                <p class="text-center text-muted mt-3 small"><a href="/pass.php">&copy; <?= date('Y'); ?> - ASIR</a></p>
            </div>
        </div>
    </div>
</body>
</html>