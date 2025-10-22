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


function extraerPreguntasRespuestasv1($html) {
    $preguntas = [];
    $dom = new DOMDocument('1.0', 'UTF-8');
    // Forzar encoding UTF-8
    @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
    // Buscar bloques de pregunta con clases que contengan 'que' y 'multichoice' (más tolerante)
    foreach ($dom->getElementsByTagName('div') as $div) {
        $class = $div->getAttribute('class');
        if ($class && strpos($class, 'que') !== false && strpos($class, 'multichoice') !== false) {
            $enunciado = '';
            $respuestas = [];

            // Buscar el primer elemento que tenga 'qtext' en su clase (puede tener otras clases)
            $qtextNode = null;
            foreach ($div->getElementsByTagName('*') as $node) {
                $c = $node->getAttribute('class');
                if ($c && strpos($c, 'qtext') !== false) {
                    $qtextNode = $node;
                    break;
                }
            }
            if ($qtextNode) {
                $enunciado = trim($qtextNode->textContent);
            } else {
                // Fallback: buscar primer h4, h3 o .formulation
                foreach (['h4','h3','div'] as $tag) {
                    foreach ($div->getElementsByTagName($tag) as $n) {
                        $nc = $n->getAttribute('class');
                        if ($tag !== 'div' || ($nc && strpos($nc, 'formulation') !== false)) {
                            $text = trim($n->textContent);
                            if ($text !== '') {
                                $enunciado = $text;
                                break 2;
                            }
                        }
                    }
                }
            }

            // Buscar respuestas: elementos con clase que empiece por r\n (r1, r2...) o que contengan 'answer' o 'r'
            foreach ($div->getElementsByTagName('*') as $node) {
                $nc = $node->getAttribute('class');
                if (!$nc) continue;

                // patrón para las clases de respuesta más comunes
                if (preg_match('/\br\d+\b/', $nc) || strpos($nc, 'answer') !== false || preg_match('/\br[01]/', $nc)) {
                    // label puede estar en <span>, <label> o dentro de un <input> con atributo value
                    $label = '';
                    $texto = '';

                    // Intentar span, label, strong en ese orden
                    $span = null;
                    foreach (['span','label','strong'] as $tag) {
                        $elems = $node->getElementsByTagName($tag);
                        if ($elems->length) {
                            $span = $elems->item(0);
                            break;
                        }
                    }
                    if ($span) {
                        $label = trim($span->textContent);
                    } else {
                        // Intentar input value
                        $inputs = $node->getElementsByTagName('input');
                        if ($inputs->length) {
                            $ival = $inputs->item(0)->getAttribute('value');
                            if ($ival) $label = trim($ival);
                        }
                    }

                    // Para el texto, preferir <p>, si no, tomar el resto del texto del nodo excluyendo el label
                    $p = $node->getElementsByTagName('p');
                    if ($p->length) {
                        $texto = trim($p->item(0)->textContent);
                    } else {
                        // Construir texto a partir del nodo, removiendo el label si lo encontramos
                        $full = trim($node->textContent);
                        if ($label !== '' && strpos($full, $label) === 0) {
                            $texto = trim(substr($full, strlen($label)));
                        } else {
                            $texto = $full;
                        }
                    }

                    // Determinar si es correcta
                    $esCorrecta = strpos($nc, 'correct') !== false || strpos($nc, 'isright') !== false;

                    // Añadir solo si hay contenido relevante
                    if ($label !== '' || $texto !== '') {
                        $respuestas[] = [
                            'opcion' => $label,
                            'texto' => $texto,
                            'correcta' => $esCorrecta
                        ];
                    }
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

function extraerPreguntasRespuestas($html) {
    $preguntas = [];
    $dom = new DOMDocument('1.0', 'UTF-8');
    // Forzar encoding UTF-8
    @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
    // Buscar bloques de pregunta con clases que contengan 'que' y 'multichoice' (más tolerante)
    foreach ($dom->getElementsByTagName('div') as $div) {
        $class = $div->getAttribute('class');
        if ($class && strpos($class, 'que') !== false && strpos($class, 'multichoice') !== false) {
            $enunciado = '';
            $respuestas = [];

            // para evitar procesar el mismo nodo varias veces
            $procesados = [];

            // Buscar el primer elemento que tenga 'qtext' en su clase (puede tener otras clases)
            $qtextNode = null;
            foreach ($div->getElementsByTagName('*') as $node) {
                $c = $node->getAttribute('class');
                if ($c && strpos($c, 'qtext') !== false) {
                    $qtextNode = $node;
                    break;
                }
            }
            if ($qtextNode) {
                $enunciado = trim($qtextNode->textContent);
            } else {
                // Fallback: buscar primer h4, h3 o .formulation
                foreach (['h4','h3','div'] as $tag) {
                    foreach ($div->getElementsByTagName($tag) as $n) {
                        $nc = $n->getAttribute('class');
                        if ($tag !== 'div' || ($nc && strpos($nc, 'formulation') !== false)) {
                            $text = trim($n->textContent);
                            if ($text !== '') {
                                $enunciado = $text;
                                break 2;
                            }
                        }
                    }
                }
            }

            // Paso 1: buscar nodos que representen directamente opciones (clases rN)
            foreach ($div->getElementsByTagName('*') as $node) {
                $nc = $node->getAttribute('class');
                if (!$nc) continue;
                if (preg_match('/\br\d+\b/', $nc)) {
                    $hash = spl_object_hash($node);
                    if (isset($procesados[$hash])) continue;
                    $procesados[$hash] = true;

                    list($label, $texto) = ['', ''];
                    // label: preferir span/label/strong
                    foreach (['span','label','strong'] as $tag) {
                        $elems = $node->getElementsByTagName($tag);
                        if ($elems->length) {
                            $label = trim($elems->item(0)->textContent);
                            break;
                        }
                    }
                    if ($label === '') {
                        // intentar input value
                        $inputs = $node->getElementsByTagName('input');
                        if ($inputs->length) $label = trim($inputs->item(0)->getAttribute('value'));
                    }

                    // texto: preferir p, luego todo el texto del nodo sin el label inicial
                    $p = $node->getElementsByTagName('p');
                    if ($p->length) {
                        $texto = trim($p->item(0)->textContent);
                    } else {
                        $full = trim($node->textContent);
                        if ($label !== '' && strpos($full, $label) === 0) {
                            $texto = trim(substr($full, strlen($label)));
                        } else {
                            $texto = $full;
                        }
                    }

                    $esCorrecta = strpos($nc, 'correct') !== false || strpos($nc, 'isright') !== false;
                    if ($label !== '' || $texto !== '') {
                        $respuestas[] = ['opcion' => $label, 'texto' => $texto, 'correcta' => $esCorrecta];
                    }
                }
            }

            // Paso 2: si no se encontraron nodos rN, intentar procesar contenedores con clase 'answer'
            if (empty($respuestas)) {
                foreach ($div->getElementsByTagName('*') as $node) {
                    $nc = $node->getAttribute('class');
                    if (!$nc) continue;
                    if (strpos($nc, 'answer') !== false) {
                        // revisar hijos directos que puedan ser opciones
                        foreach ($node->childNodes as $child) {
                            if (!($child instanceof DOMElement)) continue;
                            $childHash = spl_object_hash($child);
                            if (isset($procesados[$childHash])) continue;
                            $procesados[$childHash] = true;

                            // intentar extraer label/text similar a arriba
                            $label = '';
                            $texto = '';
                            foreach (['span','label','strong'] as $tag) {
                                $elems = $child->getElementsByTagName($tag);
                                if ($elems->length) {
                                    $label = trim($elems->item(0)->textContent);
                                    break;
                                }
                            }
                            if ($label === '') {
                                $inputs = $child->getElementsByTagName('input');
                                if ($inputs->length) $label = trim($inputs->item(0)->getAttribute('value'));
                            }
                            $p = $child->getElementsByTagName('p');
                            if ($p->length) {
                                $texto = trim($p->item(0)->textContent);
                            } else {
                                $full = trim($child->textContent);
                                if ($label !== '' && strpos($full, $label) === 0) {
                                    $texto = trim(substr($full, strlen($label)));
                                } else {
                                    $texto = $full;
                                }
                            }
                            // detectar correcta buscando 'correct' en la clase del child o del node
                            $esCorrecta = (strpos($child->getAttribute('class'), 'correct') !== false) || (strpos($nc, 'correct') !== false) || (strpos($child->getAttribute('class'), 'isright') !== false);
                            if ($label !== '' || $texto !== '') {
                                $respuestas[] = ['opcion' => $label, 'texto' => $texto, 'correcta' => $esCorrecta];
                            }
                        }
                    }
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