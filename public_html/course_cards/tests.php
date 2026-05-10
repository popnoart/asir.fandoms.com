<?php /** @var array $myconfig */ ?>
<div class="card mb-3" id="tests">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" id="pendingTestsTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pending-tests-tab" data-bs-toggle="collapse" data-bs-target="#pending-tests-tab-pane" type="button" role="tab" aria-controls="pending-tests-tab-pane" aria-selected="true">Tests pendientes</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="done-tests-tab" data-bs-toggle="collapse" data-bs-target="#done-tests-tab-pane" type="button" role="tab" aria-controls="done-tests-tab-pane" aria-selected="false">Tests hechos</button>
            </li>
        </ul>
    </div>
    <div class="card-body" id="pendingTestsTabContent">
        <div class="tab-pane collapse show" id="pending-tests-tab-pane" role="tabpanel" aria-labelledby="pending-tests-tab" tabindex="0" data-bs-parent="#tests">

            <?php if (!empty($pending_tests_course)) { ?>
            <div class="d-flex justify-content-end pt-2 px-2">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#bulkPendingTestsModal">Cambiar estado de todos</button>
            </div>
            <div class="modal fade" id="bulkPendingTestsModal" tabindex="-1" aria-labelledby="bulkPendingTestsModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post" action="">
                            <div class="modal-header">
                                <h5 class="modal-title" id="bulkPendingTestsModalLabel">Cambiar estado de todos los tests pendientes</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <?php foreach ($pending_tests_course as $t) { ?>
                                    <input type="hidden" name="bulk_status_ids[]" value="<?= htmlspecialchars($t['id']); ?>">
                                <?php } ?>
                                <input type="hidden" name="bulk_status_type" value="tests">
                                <div class="mb-3">
                                    <label class="form-label">Selecciona nuevo estado:</label>
                                    <select class="form-select" name="bulk_new_status">
                                        <?php $status_options = $myconfig['tests_status'];
                                        foreach ($status_options as $opt) {
                                            if ($opt !== 'Pendiente') { ?>
                                                <option value="<?= htmlspecialchars($opt); ?>"><?= htmlspecialchars($opt); ?></option>
                                        <?php } } ?>
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

            <ul class="list-group list-group-flush">
                <?php if (!empty($pending_tests_course)) {
                    foreach ($pending_tests_course as $test => $test_data) {
                        $modal_id = 'pendingTestModal' . $test;
                ?>
                        <li class="list-group-item ">
                            <?php if(is_numeric($test_data['id'])) { ?>
                            <a href="https://campus.digitechfp.com/mod/quiz/view.php?id=<?= htmlspecialchars($test_data['id']); ?>" target="_blank"">
                                <?= htmlspecialchars($test_data['name']); ?>
                            </a>
                            <?php } else { ?> 
                                <a href="/quiz.php?course=<?= htmlspecialchars($course); ?>&quiz=<?= htmlspecialchars($test_data['id']); ?>"><?= htmlspecialchars($test_data['name']); ?></a>
                            <?php } ?>
                            <br>
                            <?php if (!empty($test_data['end'])) { ?>
                                Fin: <?= htmlspecialchars($test_data['end']); ?>
                            <?php } ?>
                            <span class=" badge <?php if ($test_data['type'] == 'Obligatoria') { ?> bg-danger text-bg-danger<?php } else { ?> bg-warning text-bg-warning<?php } ?> ms-2"><?= htmlspecialchars($test_data['type']); ?></span>
                                <span class="badge bg-secondary" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#<?= $modal_id; ?>">
                                    <?php if (!empty($test_data['status'])) {
                                        echo htmlspecialchars($test_data['status']);
                                    } else {
                                        echo 'Sin estado';
                                    } ?>
                                </span> [ <a href="/quiz.php?course=<?= htmlspecialchars($course); ?>&quiz=<?= htmlspecialchars($test_data['id']); ?>" target="_blank">Practicar</a> ]
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
                                            <input type="hidden" name="change_status_id" value="<?= htmlspecialchars($test_data['id']); ?>">
                                            <input type="hidden" name="change_status_type" value="tests">
                                            <?php $status_options = $myconfig['tests_status']; ?>
                                            <div class="mb-3">
                                                <label for="new_status_<?= $test; ?>" class="form-label">Selecciona nuevo estado:</label>
                                                <select class="form-select" id="new_status_<?= $test; ?>" name="new_status">
                                                    <?php foreach ($status_options as $opt) { ?>
                                                        <option value="<?= htmlspecialchars($opt); ?>" <?= $opt == $test_data['status'] ? ' selected' : '' ?>><?= htmlspecialchars($opt); ?></option>
                                                    <?php } ?>
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
                    <li class="list-group-item">No hay tests pendientes.</li>
                <?php } ?>
            </ul>

        </div>
        <div class="tab-pane collapse" id="done-tests-tab-pane" role="tabpanel" aria-labelledby="done-tests-tab" tabindex="0" data-bs-parent="#tests">

            <?php if (!empty($done_tests_course)) { ?>
            <div class="d-flex justify-content-end pt-2 px-2">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#bulkDoneTestsModal">Cambiar estado de todos</button>
            </div>
            <div class="modal fade" id="bulkDoneTestsModal" tabindex="-1" aria-labelledby="bulkDoneTestsModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post" action="">
                            <div class="modal-header">
                                <h5 class="modal-title" id="bulkDoneTestsModalLabel">Cambiar estado de todos los tests hechos</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <?php foreach ($done_tests_course as $t) { ?>
                                    <input type="hidden" name="bulk_status_ids[]" value="<?= htmlspecialchars($t['id']); ?>">
                                <?php } ?>
                                <input type="hidden" name="bulk_status_type" value="tests">
                                <div class="mb-3">
                                    <label class="form-label">Selecciona nuevo estado:</label>
                                    <select class="form-select" name="bulk_new_status">
                                        <?php foreach ($myconfig['tests_status'] as $opt) { ?>
                                            <option value="<?= htmlspecialchars($opt); ?>"><?= htmlspecialchars($opt); ?></option>
                                        <?php } ?>
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

            <ul class="list-group list-group-flush">
                <?php if (!empty($done_tests_course)) {
                    foreach ($done_tests_course as $test => $test_data) {
                        $modal_id = 'doneTestModal' . $test;
                ?>
                        <li class="list-group-item">
                            <?php if(is_numeric($test_data['id'])) { ?>
                            <a href="https://campus.digitechfp.com/mod/quiz/view.php?id=<?= htmlspecialchars($test_data['id']); ?>" target="_blank"">
                                <?= htmlspecialchars($test_data['name']); ?>
                            </a>
                            <?php } else { ?> 
                                <a href="/quiz.php?course=<?= htmlspecialchars($course); ?>&quiz=<?= htmlspecialchars($test_data['id']); ?>"><?= htmlspecialchars($test_data['name']); ?></a>
                            <?php } ?>
                            <br><span class="badge bg-success text-bg-success ms-2" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#<?= $modal_id; ?>">
                                <?php if (!empty($test_data['status'])) {
                                    echo htmlspecialchars($test_data['status']);
                                } else {
                                    echo 'Sin estado';
                                } ?>
                            </span> [ <a href="/quiz.php?course=<?= htmlspecialchars($course); ?>&quiz=<?= htmlspecialchars($test_data['id']); ?>" target="_blank">Practicar</a> ]
                           
                        </li>
                        <!-- Modal cambio de estado -->
                        <div class="modal fade" id="<?= $modal_id; ?>" tabindex="-1" aria-labelledby="<?= $modal_id; ?>Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="post" action="">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="<?= $modal_id; ?>Label">Cambiar estado del test</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="change_status_id" value="<?= htmlspecialchars($test_data['id']); ?>">
                                            <input type="hidden" name="change_status_type" value="tests">
                                            <?php $status_options = $myconfig['tests_status']; ?>
                                            <div class="mb-3">
                                                <label for="new_status_done_<?= $test; ?>" class="form-label">Selecciona nuevo estado:</label>
                                                <select class="form-select" id="new_status_done_<?= $test; ?>" name="new_status">
                                                    <?php foreach ($status_options as $opt) { ?>
                                                        <option value="<?= htmlspecialchars($opt); ?>" <?= $opt == $test_data['status'] ? ' selected' : '' ?>><?= htmlspecialchars($opt); ?></option>
                                                    <?php } ?>
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
                    <li class="list-group-item">No hay test hechos.</li>
                <?php } ?>
            </ul>

        </div>
    </div>

</div>