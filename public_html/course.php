<?php include $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/templates/header.php'; ?>

<h1 class="text-center" id="<?= $course; ?>"><?= $course; ?>: <?= $course_data['name']; ?></h1>
<p class="lead text-center">Profesor: <a href="mailto:<?= isset($course_data['teacher_mail']) ? htmlspecialchars($course_data['teacher_mail']) : 'no disponible'; ?>"><?= htmlspecialchars($course_data['teacher']); ?></a></p>
<ul class="nav justify-content-center mb-3">
    <?php foreach ($config['sections'] as $section => $section_data) { ?>
        <li class="nav-item"><a class="nav-link text-info" href="<?= str_replace('[id]', $course_data['id'], $section_data['url']); ?>" target="_blank"><?= $section_data['name']; ?></a></li>
    <?php } ?>
</ul>
<div class="row g-4">
    <div class="col-4">

        <div class="card mb-3" id="pending_tasks">
            <div class="card-body">
                <h5 class="card-title">Tareas pendientes</h5>
                <ul class="list-group list-group-flush">
                    <?php if (!empty($pending_tasks_course)) {
                        foreach ($pending_tasks_course as $task => $task_data) {
                            $modal_id = 'pendingTaskModal' . $task;
                    ?>
                            <li class="list-group-item ">
                                <a href="https://campus.digitechfp.com/mod/assign/view.php?id=<?= htmlspecialchars($task_data['id']); ?>" target="_blank"">
                                    <?= htmlspecialchars($task_data['name']); ?>
                                </a><br>
                                Fin: <?= htmlspecialchars($task_data['end']); ?>
                                <span class="badge <?php if ($task_data['type'] == 'Obligatoria') { ?> bg-danger text-bg-danger<?php } else { ?> bg-warning text-bg-warning<?php } ?> ms-2"><?= htmlspecialchars($task_data['type']); ?></span>
                                <span class="badge bg-secondary" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#<?= $modal_id; ?>">
                                    <?php if (!empty($task_data['status'])) {
                                        echo htmlspecialchars($task_data['status']);
                                    } else {
                                        echo 'Sin estado';
                                    } ?>
                                </span>
                            </li>

                            <!-- Modal cambio de estado -->
                            <div class="modal fade" id="<?= $modal_id; ?>" tabindex="-1" aria-labelledby="<?= $modal_id; ?>Label" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="post" action="">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="<?= $modal_id; ?>Label">Cambiar estado de la tarea</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="change_status_id" value="<?= htmlspecialchars($task_data['id']); ?>">
                                                <input type="hidden" name="change_status_type" value="tasks">
                                                <?php $status_options = $myconfig['tasks_status']; ?>
                                                <div class="mb-3">
                                                    <label for="new_status_<?= $task; ?>" class="form-label">Selecciona nuevo estado:</label>
                                                    <select class="form-select" id="new_status_<?= $task; ?>" name="new_status">
                                                        <?php foreach ($status_options as $opt): ?>
                                                            <option value="<?= htmlspecialchars($opt); ?>" <?= $opt == $task_data['status'] ? ' selected' : '' ?>><?= htmlspecialchars($opt); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Guardar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    } else { ?>
                        <li class="list-group-item">No hay tareas pendientes.</li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <div class="card mb-3" id="units">
            <div class="card-body">
                <h5 class="card-title">Unidades</h5>
                <ul class="list-group list-group-flush">
                    <?php foreach ($course_data['units'] as $unit => $unit_data) {
                        $modal_id = 'unitModal' . $unit;
                    ?>
                        <li class="list-group-item">
                            <?php if (!empty($unit_data['link'])) { ?>
                                <a href="<?= $unit_data['link']; ?>" target="_blank"><?= htmlspecialchars($unit_data['name']); ?></a>
                            <?php } else { ?>
                                <?= htmlspecialchars($unit_data['name']); ?>
                            <?php } ?>
                            <?php if (!empty($unit_data['status'])) { ?>
                                <span class="badge bg-secondary" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#<?= $modal_id; ?>">
                                    <?php if (!empty($unit_data['status'])) {
                                        echo htmlspecialchars($unit_data['status']);
                                    } else {
                                        echo 'Sin estado';
                                    } ?>
                                </span>
                            <?php } ?>
                        </li>

                        <!-- Modal cambio de estado unidad -->
                        <div class="modal fade" id="<?= $modal_id; ?>" tabindex="-1" aria-labelledby="<?= $modal_id; ?>Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="post" action="">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="<?= $modal_id; ?>Label">Cambiar estado de la unidad</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="change_status_id" value="<?= htmlspecialchars(isset($unit_data['id']) ? $unit_data['id'] : $unit); ?>">
                                            <input type="hidden" name="change_status_type" value="units">
                                            <?php $status_options = $myconfig['units_status']; ?>
                                            <div class="mb-3">
                                                <label for="new_status_unit_<?= $unit; ?>" class="form-label">Selecciona nuevo estado:</label>
                                                <select class="form-select" id="new_status_unit_<?= $unit; ?>" name="new_status">
                                                    <?php foreach ($status_options as $opt): ?>
                                                        <option value="<?= htmlspecialchars($opt); ?>" <?= $opt == $unit_data['status'] ? ' selected' : '' ?>><?= htmlspecialchars($opt); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <div class="card mb-3" id="resources">
            <div class="card-body">
                <h5 class="card-title">Recursos</h5>
                <ul class="list-group list-group-flush">
                    <?php foreach ($course_data['resources'] as $resource => $resource_data) {  ?>
                        <li class="list-group-item">
                            <?php if (!empty($resource_data['link'])) { ?>
                                <a href="<?= $resource_data['link']; ?>" target="_blank"><?= htmlspecialchars($resource_data['name']); ?></a>
                            <?php } else { ?>
                                <?= htmlspecialchars($resource_data['name']); ?>
                            <?php } ?>
                            <?php if (!empty($resource_data['status'])) { ?>
                                <span class="badge bg-secondary" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#<?= $modal_id; ?>">
                                    <?php if (!empty($resource_data['status'])) {
                                        echo htmlspecialchars($resource_data['status']);
                                    } else {
                                        echo 'Sin estado';
                                    } ?>
                                </span>
                            <?php } ?>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

    </div>

    <div class="col-4">

        <div class="card mb-3" id="tests">
            <div class="card-body">
                <h5 class="card-title">Tests</h5>
                <ul class="list-group list-group-flush">
                    <?php foreach ($course_data['tests'] as $test => $test_data) {?>
                        <li class="list-group-item">
                            <?php if (!empty($test_data['link'])) { ?>
                                <a href="<?= $test_data['link']; ?>" target="_blank"><?= htmlspecialchars($test_data['name']); ?></a>
                            <?php } else { ?>
                                <?= htmlspecialchars($test_data['name']); ?>
                            <?php } ?>
                            <?php if (!empty($test_data['status'])) { ?>
                                <span class="badge bg-secondary" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#<?= $modal_id; ?>">
                                    <?php if (!empty($test_data['status'])) {
                                        echo htmlspecialchars($test_data['status']);
                                    } else {
                                        echo 'Sin estado';
                                    } ?>
                                </span>
                            <?php } ?>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <div class="card mb-3" id="notes">
            <div class="card-body">
                <h5 class="card-title">Apuntes</h5>
                <ul class="list-group list-group-flush">
                    <?php foreach ($course_data['notes'] as $note => $note_data) {
                        $modal_id = 'noteModal' . $note;
                    ?>
                        <li class="list-group-item">
                            <?php if (!empty($note_data['link'])) { ?>
                                <a href="<?= $note_data['link']; ?>" target="_blank"><?= htmlspecialchars($note_data['name']); ?></a>
                            <?php } else { ?>
                                <?= htmlspecialchars($note_data['name']); ?>
                            <?php } ?>
                            <?php if (!empty($note_data['status'])) { ?>
                                <span class="badge bg-secondary" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#<?= $modal_id; ?>">
                                    <?php if (!empty($note_data['status'])) {
                                        echo htmlspecialchars($note_data['status']);
                                    } else {
                                        echo 'Sin estado';
                                    } ?>
                                </span>
                            <?php } ?>
                        </li>

                        <!-- Modal cambio de estado apunte -->
                        <div class="modal fade" id="<?= $modal_id; ?>" tabindex="-1" aria-labelledby="<?= $modal_id; ?>Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="post" action="">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="<?= $modal_id; ?>Label">Cambiar estado del apunte</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="change_status_id" value="<?= htmlspecialchars(isset($note_data['id']) ? $note_data['id'] : $note); ?>">
                                            <input type="hidden" name="change_status_type" value="notes">
                                            <?php $status_options = $myconfig['notes_status']; ?>
                                            <div class="mb-3">
                                                <label for="new_status_note_<?= $note; ?>" class="form-label">Selecciona nuevo estado:</label>
                                                <select class="form-select" id="new_status_note_<?= $note; ?>" name="new_status">
                                                    <?php foreach ($status_options as $opt): ?>
                                                        <option value="<?= htmlspecialchars($opt); ?>" <?= $opt == $note_data['status'] ? ' selected' : '' ?>><?= htmlspecialchars($opt); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </ul>
            </div>
        </div>

    </div>

    <div class="col-4">

        <div class="card mb-3 border-0" id="calendar">
            <div class="agenda-list">
                <?php
                // Mostrar las próximas 6 clases de este curso
                $clases = [];
                if (!empty($calendar_events)) {
                    foreach ($calendar_events as $ev) {
                        if (isset($ev['SUMMARY']) && stripos($ev['SUMMARY'], 'Clase') === 0 && isset($ev['DTSTART']) && isset($ev['COURSE']) && $ev['COURSE'] === $course) {
                            $clases[] = $ev;
                        }
                    }
                    // Ordenar por DTSTART (fecha de inicio)
                    usort($clases, function ($a, $b) {
                        return strcmp($a['DTSTART'], $b['DTSTART']);
                    });
                    // Filtrar solo las próximas 6 clases a partir de hoy
                    $now = date('Ymd\THis\Z');
                    $clases = array_filter($clases, function ($ev) use ($now) {
                        return $ev['DTSTART'] >= $now;
                    });
                    $clases = array_slice($clases, 0, 6);
                    if (!empty($clases)) {
                        echo '<div class="d-flex flex-column gap-2">';
                        foreach ($clases as $clase) {
                            // Formatear fecha: día grande, mes y día semana pequeño
                            $date_big = $date_small = '';
                            if (isset($clase['DTSTART'])) {
                                $dt = null;
                                if (preg_match('/^\d{8}T\d{6}Z$/', $clase['DTSTART'])) {
                                    $dt = DateTime::createFromFormat('Ymd\THis\Z', $clase['DTSTART'], new DateTimeZone('UTC'));
                                    if ($dt) $dt->setTimezone(new DateTimeZone('Europe/Madrid'));
                                }
                                if ($dt) {
                                    $meses = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
                                    $dias = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
                                    $date_big = $dt->format('j');
                                    $mes = $meses[(int)$dt->format('n') - 1];
                                    $dow = $dias[(int)$dt->format('w')];
                                    $date_small = "$mes, $dow";
                                } else {
                                    $date_big = htmlspecialchars($clase['DTSTART']);
                                }
                            }
                            $summary = htmlspecialchars($clase['SUMMARY']);
                            // Calcular color de fondo según la fecha
                            $fondo = '#f8fafc';
                            $clase_fondo = '';
                            if (isset($dt) && $dt) {
                                $hoy = (new DateTime('now', new DateTimeZone('Europe/Madrid')))->setTime(0,0,0);
                                $fecha_ev = clone $dt; $fecha_ev->setTime(0,0,0);
                                $diff = (int)$hoy->diff($fecha_ev)->format('%R%a');
                                if ($diff === 0) {
                                    $clase_fondo = ' bg-danger text-bg-danger';
                                } elseif ($diff > 0 && $diff <= 3) {
                                    $clase_fondo = ' bg-warning text-bg-warning';
                                }
                            }
                            echo '<div class="agenda-event card shadow-sm border-0'.$clase_fondo.'" style="background: #f8fafc;">';
                            echo '<div class="card-body d-flex align-items-center">';
                            echo '<div class="me-3 text-center" style="min-width:60px;">';
                            echo '<div class="agenda-date text-primary">';
                            echo '<span class="agenda-date-big text-primary-emphasis">' . $date_big . '</span><br>';
                            if ($date_small) echo '<span class="agenda-date-small">' . $date_small . '</span>';
                            echo '</div>';
                            echo '<div><i class="bi bi-calendar-event" style="font-size:1.5em;color:#0d6efd;"></i></div>';
                            echo '</div>';
                            echo '<div class="flex-grow-1">';
                            echo '<div class="agenda-title fw-semibold" style="font-size:1.1em;">' . $summary . '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                        echo '</div>';
                    } else {
                        echo '<div class="alert alert-secondary">No hay clases próximas.</div>';
                    }
                } else {
                    echo '<div class="alert alert-warning">Hay problemas con el archivo calendar.json</div>';
                }
                ?>
            </div>
        </div>

        <div class="card mb-3" id="done_tasks">
            <div class="card-body">
                <h5 class="card-title">Tareas terminadas</h5>
                <ul class="list-group list-group-flush">
                    <?php if (!empty($done_tasks_course)) {
                        foreach ($done_tasks_course as $task => $task_data) {
                            $modal_id = 'doneTaskModal' . $task;
                    ?>
                            <li class="list-group-item ">
                                <a href="https://campus.digitechfp.com/mod/assign/view.php?id=<?= htmlspecialchars($task_data['id']); ?>" target="_blank"">
                                    <?= htmlspecialchars($task_data['name']); ?>
                                </a><br>
                                Fin: <?= htmlspecialchars($task_data['end']); ?>
                                <span class="badge bg-success text-bg-success ms-2" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#<?= $modal_id; ?>">
                                    <?php if (!empty($task_data['status'])) {
                                        echo htmlspecialchars($task_data['status']);
                                    } else {
                                        echo 'Sin estado';
                                    } ?>
                                </span>
                            </li>

                            <!-- Modal cambio de estado -->
                            <div class="modal fade" id="<?= $modal_id; ?>" tabindex="-1" aria-labelledby="<?= $modal_id; ?>Label" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="post" action="">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="<?= $modal_id; ?>Label">Cambiar estado de la tarea</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="change_status_id" value="<?= htmlspecialchars($task_data['id']); ?>">
                                                <input type="hidden" name="change_status_type" value="tasks">
                                                <?php $status_options = $myconfig['tasks_status']; ?>
                                                <div class="mb-3">
                                                    <label for="new_status_done_<?= $task; ?>" class="form-label">Selecciona nuevo estado:</label>
                                                    <select class="form-select" id="new_status_done_<?= $task; ?>" name="new_status">
                                                        <?php foreach ($status_options as $opt): ?>
                                                            <option value="<?= htmlspecialchars($opt); ?>" <?= $opt == $task_data['status'] ? ' selected' : '' ?>><?= htmlspecialchars($opt); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Guardar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    } else { ?>
                        <li class="list-group-item">No hay tareas terminadas.</li>
                    <?php } ?>
                </ul>
            </div>
        </div>

    </div>

</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/templates/footer.php'; ?>