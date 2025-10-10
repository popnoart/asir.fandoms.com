<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php'; 
include $_SERVER['DOCUMENT_ROOT'] . '/assets/templates/header.php'; 
?>

<div class="row">
    <div class="col-12 col-md-6">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Tareas pendientes</h5>
                <ul class="list-group  list-group-flush">
                    <?php if (!empty($pending_tasks)) {
                        foreach ($pending_tasks as $task=> $task_data) {
                    ?>
                            <li class="list-group-item ">
                                <?php if (!empty($task_data['course'])): ?>
                                    <a href="/course.php?course=<?= htmlspecialchars($task_data['course']); ?>"><span class="badge bg-info text-bg-info ms-2"><?= htmlspecialchars($task_data['course']); ?></span></a>
                                <?php endif; ?>
                                <a href="https://campus.digitechfp.com/mod/assign/view.php?id=<?= htmlspecialchars($task_data['id']); ?>" target="_blank"">
                                    <?= htmlspecialchars($task_data['name']); ?>
                                </a><br>
                                Fin: <?= htmlspecialchars($task_data['end']); ?>
                                <span class="badge <?php if($task_data['type'] == 'Obligatoria'){ ?> bg-danger text-bg-danger<?php }else{ ?> bg-warning text-bg-warning<?php } ?> ms-2"><?= htmlspecialchars($task_data['type']); ?></span>
                                <span class="badge text-bg-secondary ms-2" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#statusModal<?= $task; ?>"><?= htmlspecialchars($task_data['status']); ?></span>
                            </li>
                            <!-- Modal cambio de estado -->
                            <div class="modal fade" id="statusModal<?= $task; ?>" tabindex="-1" aria-labelledby="statusModal<?= $task; ?>Label" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="post" action="">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="statusModal<?= $task; ?>Label">Cambiar estado de la tarea</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="change_status_type" value="tasks">
                                                <input type="hidden" name="change_status_id" value="<?= htmlspecialchars($task_data['id']); ?>">
                                                <?php
                                                $status_options = $myconfig['tasks_status'];
                                                ?>
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

                        <?php
                        } // fin foreach
                    } // fin if !empty
                    else { ?>
                        <li class="list-group-item">No hay tareas pendientes.</li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card mb-3 border-0">
            <div class="agenda-list">
                <?php
                // Mostrar las próximas 10 clases (SUMMARY empieza por 'Clase')
                $clases = [];
                if (!empty($calendar_events)) {
                    foreach ($calendar_events as $ev) {
                        if (isset($ev['SUMMARY']) && stripos($ev['SUMMARY'], 'Clase') === 0 && isset($ev['DTSTART'])) {
                            $clases[] = $ev;
                        }
                    }
                    // Ordenar por DTSTART (fecha de inicio)
                    usort($clases, function ($a, $b) {
                        return strcmp($a['DTSTART'], $b['DTSTART']);
                    });
                    // Filtrar solo las próximas 10 clases a partir de hoy
                    $now = date('Ymd\THis\Z');
                    $clases = array_filter($clases, function ($ev) use ($now) {
                        return $ev['DTSTART'] >= $now;
                    });
                    $clases = array_slice($clases, 0, 10);
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
                            $course = !empty($clase['COURSE']) ? htmlspecialchars($clase['COURSE']) : '';
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
                            if ($course) {
                                echo '<span class="badge bg-info ms-1">' . $course . '</span>';
                            }
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
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/templates/footer.php'; ?>