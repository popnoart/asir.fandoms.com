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

$courses_path = 'https://popnoart.com/data/courses.json';
$courses_path = $_SERVER['DOCUMENT_ROOT'] . '/data/courses.json';
$all_courses = json_decode(file_get_contents($courses_path), true);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Descarga y OCR unidades de Digitech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-4">
        <h1 class="mb-4">Descarga y OCR unidades de Digitech</h1>

        <div class="row row-cols-1 row-cols-md-3 g-4">

            <?php foreach ($all_courses as $course => $course_data) {?>
                <div class="col">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><?= $course; ?>: <?= $course_data['name']; ?> </h5>
                                
                <ul class="list-group list-group-flush">
                    <?php foreach ($course_data['units'] as $unit => $unit_data) {
                        $unit_stripped = str_replace($course . '-', '', $unit);
                        $folder_downloads = $_SERVER['DOCUMENT_ROOT'] . '/files/' . $course . '/DOWNLOADS/';
                        if (!file_exists($folder_downloads . 'U' . $unit_stripped . '/')) {
                            mkdir($folder_downloads . 'U' . $unit_stripped . '/', 0777, true);
                        }
                        if (!file_exists($folder_downloads . 'course' . $unit_stripped . '.json')) {
                            fopen($folder_downloads . 'course' . $unit_stripped . '.json', "w");
                        }
                    ?>
                    <li class="list-group-item">
                        <?php if(!empty($unit_data['folder'])){ ?>
                        <a role="button" class="card-link" data-bs-toggle="modal" data-bs-target="#download<?= $unit; ?>">
                        <?= $unit_stripped . ' ' . $unit_data['name']; ?>
                        </a>
                        <?php }else{ ?>
                        <?= $unit_stripped . ' ' . $unit_data['name']; ?>
                        <?php } ?>
                    </li>
                    <!-- Modal download-->
                    <div class="modal fade" id="download<?= $unit; ?>" tabindex="-1" aria-labelledby="download<?= $unit; ?>Label" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="download<?= $unit; ?>Label">Descargar: U<?= $unit_stripped . ' ' . $unit_data['name']; ?></h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <?php if (file_exists($folder_downloads . 'U'.$unit_stripped . '.pdf')) {
                                    echo '<a href="' . $folder_downloads .'U'.$unit_stripped . '.pdf" target="_blank" class="card-link">PDF imágenes</a>';
                                }else{
                                    echo '<p>No se ha generado el PDF aún.</p>';
                                } 
                                if (file_exists($folder_downloads . 'U'.$unit_stripped . '-ocr.pdf')) {
                                    echo '<a href="' . $folder_downloads . 'U'.$unit_stripped . '-ocr.pdf" target="_blank" class="card-link">PDF texto</a>';
                                }else{
                                    echo '<p>No se ha generado el OCR aún.</p>';
                                } 
                                ?> 
                                <a href="https://campus.digitechfp.com/pluginfile.php/<?= $unit_data['folder']; ?>/mod_scorm/content/<?= $unit_data['folder_number']; ?>/data/course.json" class="card-link" target="_blank">Descargar json</a>
                                <hr>
                                <h3 class="fs-5">Generar OCR</h3>
                                <p><code>ocrmypdf -l spa <?= $folder_downloads . 'U'.$unit_stripped . '.pdf ' . $folder_downloads . 'U'.$unit_stripped . '-ocr.pdf'; ?></code></p>
                                <hr>
                                <h3 class="fs-5">Descargar imágenes</h3>
                                <ol class="list-group list-group-flush list-group-numbered">

                                <?php if (file_exists($folder_downloads. 'course' . $unit_stripped . '.json')) {
                                    $course_array = json_decode(file_get_contents($folder_downloads. 'course' . $unit_stripped . '.json'), true);
                                      if(!empty($course_array['pages']) && is_array($course_array['pages'])) {
                                        foreach ($course_array['pages'] as $key => $page_data) {
                                            echo '<li class="list-group-item">
                                                    <a href="https://campus.digitechfp.com/pluginfile.php/' . $unit_data['folder'] . '/mod_scorm/content/' . $unit_data['folder_number'] . '/assets/' . $page_data['filename'] . '" target="_blank">' . $page_data['title'] . '</a>';
                                            if (file_exists($folder_downloads .  $course . '/U' . $unit_stripped . '/' . $page_data['filename'])) {
                                                echo ' (<a href="/' . $course . '/U' . $unit_stripped . '/' . $page_data['filename'] . '">Local</a>)</li>';
                                            }
                                        }
                                    }else{
                                        echo 'Algo pasa con el JSON del SCORM.';
                                    }
                                }else{
                                    echo 'No se ha descargado el JSON del SCORM aún.';
                                }
                                ?>
                            </ol>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-primary">Guardar cambios</button>
                            </div>
                            </div>
                        </div>
                    </div>
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