<?php // Card: Apuntes
?>
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
