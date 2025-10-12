<?php // Card: Unidades
?>
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
