<?php // Card: Tareas pendientes
?>
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
