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
if ($_SESSION['account'] != 'popnoart') {
    echo 'No tienes permiso para acceder a esta página ' . $_SESSION['account'];
    exit;
}

$courses_path = 'https://popnoart.com/data/courses.json';
$courses_path = $_SERVER['DOCUMENT_ROOT'] . '/data/courses.json';
$all_courses = json_decode(file_get_contents($courses_path), true);


function extraerPreguntasRespuestas($html) {
    $preguntas = [];
    $dom = new DOMDocument('1.0', 'UTF-8');
    // Forzar encoding UTF-8
    @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);

    foreach ($dom->getElementsByTagName('div') as $div) {
        if ($div->getAttribute('class') && strpos($div->getAttribute('class'), 'que multichoice') !== false) {
            $enunciado = '';
            $respuestas = [];

            foreach ($div->getElementsByTagName('div') as $subdiv) {
                if ($subdiv->getAttribute('class') === 'qtext') {
                    $enunciado = trim($subdiv->textContent);
                }
            }

            foreach ($div->getElementsByTagName('div') as $ansDiv) {
                if ($ansDiv->getAttribute('class') && preg_match('/^r[01]/', $ansDiv->getAttribute('class'))) {
                    $label = $ansDiv->getElementsByTagName('span')[0]->textContent;
                    $texto = $ansDiv->getElementsByTagName('p')[0]->textContent;
                    $esCorrecta = strpos($ansDiv->getAttribute('class'), 'correct') !== false;
                    $respuestas[] = [
                        'opcion' => $label,
                        'texto' => $texto,
                        'correcta' => $esCorrecta
                    ];
                }
            }

            $preguntas[] = [
                'enunciado' => $enunciado,
                'respuestas' => $respuestas
            ];
        }
    }
    return $preguntas;
}

if (isset($_GET['course']) && isset($_GET['test'])) {
    // Leer el archivo HTML en UTF-8
    $html_path = $_SERVER['DOCUMENT_ROOT'] . '/files/' . $_GET['course'] . '/TESTS/' . $_GET['test'] . '.html';
    $html = file_get_contents($html_path);
    if (mb_detect_encoding($html, 'UTF-8', true) === false) {
        $html = utf8_encode($html);
    }
    $resultado = extraerPreguntasRespuestas($html);
    // Guardar en JSON
    $json_path = $_SERVER['DOCUMENT_ROOT'] . '/data/tests/' . $_GET['test'] . '.json';
    file_put_contents($json_path, json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Cuestionarios y autoevaluaciones de Digitech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <div class="container my-4">
        <h1 class="mb-4">Cuestionarios y autoevaluaciones de Digitech</h1>

        <div class="row row-cols-1 row-cols-md-3 g-4">

            <?php foreach ($all_courses as $course => $course_data) { ?>
                <div class="col">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><?= $course; ?>: <?= $course_data['name']; ?> </h5>

                            <ul class="list-group list-group-flush">
                                <?php foreach ($course_data['tests'] as $test => $test_data) {
                                    $folder_downloads = $_SERVER['DOCUMENT_ROOT'] . '/files/' . $course . '/TESTS/';
                                    if (!file_exists($folder_downloads . $test . '.html')) {
                                        fopen($folder_downloads . $test . '.html', "w");
                                    }
                                ?>
                                    <li class="list-group-item">
                                        <a href="https://campus.digitechfp.com/mod/quiz/view.php?id=<?= $test; ?>" target="_blank"><?= $test_data['name']; ?></a> <a class="btn btn-primary-outline" href="tests.php?course=<?= $course; ?>&test=<?= $test; ?>"><?php if (!file_exists( $_SERVER['DOCUMENT_ROOT'] . '/data/tests/' . $test . '.json')) { ?> <i class="fas fa-cog"></i>
                                        <?php } else { ?><i class="fas fa-redo"></i><?php } ?></a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>