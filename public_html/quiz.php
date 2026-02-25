<?php include $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/templates/header.php'; ?>

<h1 class="text-center" id="<?= $course; ?>"><?= $course; ?>: <?= $course_data['name']; ?></h1>
<p class="lead text-center">Profesor: <a href="mailto:<?= isset($course_data['teacher_mail']) ? htmlspecialchars($course_data['teacher_mail']) : 'no disponible'; ?>"><?= htmlspecialchars($course_data['teacher']); ?></a></p>
<ul class="nav justify-content-center mb-3">
    <?php foreach ($config['sections'] as $section => $section_data) { ?>
        <li class="nav-item"><a class="nav-link text-info" href="<?= str_replace('[id]', $course_data['id'], $section_data['url']); ?>" target="_blank"><?= $section_data['name']; ?></a></li>
    <?php } ?>
</ul>

<div class="row justify-content-center">
    <h2 class="text-center"><?= $quiz_data['name']; ?></h2>
    <?php
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/data/tests/' . $quiz . '.json') === false) {
        echo '<div class="alert alert-danger" role="alert">Todavía no he hecho o subido este cuestionario.</div>';
        include $_SERVER['DOCUMENT_ROOT'] . '/assets/templates/footer.php';
        exit;
    } else {

        if ($quiz === 'SAD_FEBRERO') {
            echo '<div class="alert alert-danger" role="alert">Todavía no tengo las respuestas correctas. Es un 8,75. He marcado como correctas las que he puesto yo, así que tiene fallos.</div>';
        }
        $quiz_path = $_SERVER['DOCUMENT_ROOT'] . '/data/tests/' . $quiz . '.json';
        $quiz_content = json_decode(file_get_contents($quiz_path), true);
    ?>
        <form id="quizForm" class="col-12 col-md-8">
            <?php if (!empty($quiz_content[0]['pre'])) {
                $pre = $quiz_content[0]['pre'];
                unset($quiz_content[0]);
                if (isset($pre[0]['file'])) {
                    echo '<div class="mb-4 d-flex justify-content-center"><audio controls><source src="/data/tests/audio/' . htmlspecialchars($pre[0]['file'], ENT_QUOTES, 'UTF-8') . '" type="audio/mpeg">Tu navegador no soporta el elemento de audio.</audio></div>';
                } elseif (isset($pre[0]['text'])) {
                    echo $pre[0]['text'];
                }
            }
            ?>
            <?php foreach ($quiz_content as $idx => $pregunta): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <strong>Pregunta <?php echo $idx + 1; ?>:</strong> <?php echo htmlspecialchars($pregunta['enunciado'], ENT_QUOTES, 'UTF-8'); ?>
                        <?php if (!empty($pregunta['imagen'])){ ?>
                            <div class="mt-2 text-center">
                                <img src="/data/tests/images/<?php echo htmlspecialchars($pregunta['imagen'], ENT_QUOTES, 'UTF-8'); ?>" alt="Imagen pregunta <?php echo $idx + 1; ?>" class="img-fluid rounded shadow quiz-img-thumb" style="max-width:400px;cursor:pointer;" onclick="mostrarLightbox(this.src)">
                            </div>
                        <?php } ?> <?php if (!empty($pregunta['code'])){ ?>
                            <div class="mt-2 text-success">
                                <pre style="overflow-x:auto;"><code><?php echo htmlspecialchars($pregunta['code'], ENT_QUOTES, 'UTF-8'); ?></code></pre>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <?php foreach ($pregunta['respuestas'] as $rid => $respuesta): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pregunta_<?php echo $idx; ?>" id="pregunta_<?php echo $idx; ?>_opcion_<?php echo $rid; ?>" value="<?php echo $rid; ?>">
                                <label class="form-check-label" for="pregunta_<?php echo $idx; ?>_opcion_<?php echo $rid; ?>">
                                    <?php echo htmlspecialchars($respuesta['opcion'] . ' ' . $respuesta['texto'], ENT_QUOTES, 'UTF-8'); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                        <button type="button" class="btn btn-primary mt-3" onclick="evaluarPregunta(<?php echo $idx; ?>)">Respuesta</button>
                        <div id="feedback_<?php echo $idx; ?>" class="mt-2"></div>
                        <div id="correct_<?php echo $idx; ?>" class="mt-1"></div>
                    </div>
                </div>
            <?php endforeach; ?>
            <button type="button" class="btn btn-success mb-4" onclick="evaluarTodo()">Resolver</button>
            <div id="feedback_total" class="mt-3"></div>
            <div id="correct_total" class="mt-3"></div>
        </form>
    <?php  } ?>
</div>

<!-- Lightbox modal -->
<div id="quizLightbox" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.8);z-index:9999;align-items:center;justify-content:center;">
    <span style="position:absolute;top:20px;right:40px;font-size:2rem;color:#fff;cursor:pointer;z-index:10001;" onclick="cerrarLightbox()">&times;</span>
    <img id="quizLightboxImg" src="" alt="Imagen ampliada" style="max-width:90vw;max-height:90vh;box-shadow:0 0 30px #000;border-radius:10px;">
</div>

<script>
    function mostrarLightbox(src) {
        var lb = document.getElementById('quizLightbox');
        var img = document.getElementById('quizLightboxImg');
        img.src = src;
        lb.style.display = 'flex';
        lb.style.alignItems = 'center';
        lb.style.justifyContent = 'center';
    }

    function cerrarLightbox() {
        var lb = document.getElementById('quizLightbox');
        var img = document.getElementById('quizLightboxImg');
        img.src = '';
        lb.style.display = 'none';
    }
    // Respuestas correctas
    const respuestasCorrectas = <?php
                                $corrects = [];
                                foreach ($quiz_content as $pregunta) {
                                    foreach ($pregunta['respuestas'] as $rid => $respuesta) {
                                        if ($respuesta['correcta']) {
                                            $corrects[] = $rid;
                                            break;
                                        }
                                    }
                                }
                                echo json_encode($corrects);
                                ?>;

    function mostrarCorrecta(idx) {
        const radios = document.getElementsByName('pregunta_' + idx);
        let correcta = respuestasCorrectas[idx];
        let label = document.querySelector(`label[for='pregunta_${idx}_opcion_${correcta}']`);
        if (label) {
            label.classList.add('fw-bold', 'text-primary');
        }
        let correctDiv = document.getElementById('correct_' + idx);
        correctDiv.innerHTML = `<span class="text-info">Respuesta correcta: <strong>${label ? label.textContent : ''}</strong></span>`;
    }

    function evaluarPregunta(idx) {
        const radios = document.getElementsByName('pregunta_' + idx);
        let seleccion = -1;
        for (let i = 0; i < radios.length; i++) {
            if (radios[i].checked) {
                seleccion = parseInt(radios[i].value);
                break;
            }
        }
        let feedback = document.getElementById('feedback_' + idx);
        if (seleccion === -1) {
            feedback.innerHTML = '<span class="text-warning">Sin responder.<br>La respuesta correcta era:</span>';
        } else if (seleccion === respuestasCorrectas[idx]) {
            feedback.innerHTML = '<span class="text-success">¡Correcto!</span>';
        } else {
            feedback.innerHTML = '<span class="text-danger">Incorrecto.</span>';
        }
        mostrarCorrecta(idx);
    }

    function evaluarTodo() {
        let total = respuestasCorrectas.length;
        let aciertos = 0;
        let correctasHtml = '';
        for (let idx = 0; idx < total; idx++) {
            const radios = document.getElementsByName('pregunta_' + idx);
            let seleccion = -1;
            for (let i = 0; i < radios.length; i++) {
                if (radios[i].checked) {
                    seleccion = parseInt(radios[i].value);
                    break;
                }
            }
            let feedback = document.getElementById('feedback_' + idx);
            if (seleccion === respuestasCorrectas[idx]) {
                feedback.innerHTML = '<span class="text-success">¡Correcto!</span>';
                aciertos++;
            } else if (seleccion !== -1) {
                feedback.innerHTML = '<span class="text-danger">Incorrecto.</span>';
            } else {
                feedback.innerHTML = '<span class="text-warning">Sin responder. <br>La respuesta correcta era:</span>';
            }
            mostrarCorrecta(idx);
            let label = document.querySelector(`label[for='pregunta_${idx}_opcion_${respuestasCorrectas[idx]}']`);
            correctasHtml += `<div><span class='text-info'>Pregunta ${idx+1} correcta: <strong>${label ? label.textContent : ''}</strong></span></div>`;
        }
        let feedbackTotal = document.getElementById('feedback_total');
        feedbackTotal.innerHTML = `<strong>Resultado:</strong> ${aciertos} de ${total} correctas.`;
        let correctTotal = document.getElementById('correct_total');
        correctTotal.innerHTML = correctasHtml;
    }
</script>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/templates/footer.php'; ?>