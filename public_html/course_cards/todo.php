<div class="card mb-3" id="todo">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <ul class="nav nav-tabs card-header-tabs" id="todoTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pending-todo-tab" data-bs-toggle="collapse" data-bs-target="#pending-todo-tab-pane" type="button" role="tab" aria-controls="pending-todo-tab-pane" aria-selected="true">TODO pendientes</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="done-todo-tab" data-bs-toggle="collapse" data-bs-target="#done-todo-tab-pane" type="button" role="tab" aria-controls="done-todo-tab-pane" aria-selected="false">TODO terminados</button>
                </li>
            </ul>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addTodoModalCourse">+ Añadir</button>
        </div>
    </div>
    <div class="card-body" id="todoTabContent">
        <div class="tab-pane collapse show" id="pending-todo-tab-pane" role="tabpanel" aria-labelledby="pending-todo-tab" tabindex="0" data-bs-parent="#todo">
            
            <ul class="list-group list-group-flush">
                <?php if (!empty($pending_todos_course)) {
                    foreach ($pending_todos_course as $todo_data) {
                        $modal_id = 'pendingTodoModal' . $todo_data['id'];
                ?>
                        <li class="list-group-item">
                            <?php if (!empty($todo_data['link'])): ?>
                                <a href="<?= htmlspecialchars($todo_data['link']); ?>" target="_blank">
                                    <?= htmlspecialchars($todo_data['name']); ?>
                                </a>
                            <?php else: ?>
                                <?= htmlspecialchars($todo_data['name']); ?>
                            <?php endif; ?>
                            <span class="badge bg-secondary" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#<?= $modal_id; ?>">
                                <?php if (!empty($todo_data['status'])) {
                                    echo htmlspecialchars($todo_data['status']);
                                } else {
                                    echo 'Sin estado';
                                } ?>
                            </span>
                            <button class="btn btn-sm btn-danger float-end" data-bs-toggle="modal" data-bs-target="#deleteTodoModal<?= $todo_data['id']; ?>">×</button>
                        </li>
                        <!-- Modal cambio de estado -->
                        <div class="modal fade" id="<?= $modal_id; ?>" tabindex="-1" aria-labelledby="<?= $modal_id; ?>Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="post" action="">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="<?= $modal_id; ?>Label">Cambiar estado del TODO</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="change_todo_status" value="1">
                                            <input type="hidden" name="todo_id" value="<?= htmlspecialchars($todo_data['id']); ?>">
                                            <?php $status_options = $myconfig['todo_status']; ?>
                                            <div class="mb-3">
                                                <label for="new_status_<?= $todo_data['id']; ?>" class="form-label">Selecciona nuevo estado:</label>
                                                <select class="form-select" id="new_status_<?= $todo_data['id']; ?>" name="new_status">
                                                    <?php foreach ($status_options as $opt) { ?>
                                                        <option value="<?= htmlspecialchars($opt); ?>" <?= $opt == $todo_data['status'] ? ' selected' : '' ?>><?= htmlspecialchars($opt); ?></option>
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
                        <!-- Modal eliminar -->
                        <div class="modal fade" id="deleteTodoModal<?= $todo_data['id']; ?>" tabindex="-1" aria-labelledby="deleteTodoModal<?= $todo_data['id']; ?>Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="post" action="">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteTodoModal<?= $todo_data['id']; ?>Label">Eliminar TODO</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Estás seguro de que quieres eliminar este TODO?</p>
                                            <input type="hidden" name="delete_todo" value="1">
                                            <input type="hidden" name="todo_id" value="<?= htmlspecialchars($todo_data['id']); ?>">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php }
                } else { ?>
                    <li class="list-group-item">No hay TODOs pendientes.</li>
                <?php } ?>
            </ul>
        </div>

        <div class="tab-pane collapse" id="done-todo-tab-pane" role="tabpanel" aria-labelledby="done-todo-tab" tabindex="0" data-bs-parent="#todo">
                
            <ul class="list-group list-group-flush">
                <?php if (!empty($done_todos_course)) {
                    foreach ($done_todos_course as $todo_data) {
                        $modal_id = 'doneTodoModal' . $todo_data['id'];
                ?>
                        <li class="list-group-item">
                            <?php if (!empty($todo_data['link'])): ?>
                                <a href="<?= htmlspecialchars($todo_data['link']); ?>" target="_blank">
                                    <?= htmlspecialchars($todo_data['name']); ?>
                                </a>
                            <?php else: ?>
                                <?= htmlspecialchars($todo_data['name']); ?>
                            <?php endif; ?>
                            <span class="badge bg-success text-bg-success ms-2" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#<?= $modal_id; ?>">
                                <?php if (!empty($todo_data['status'])) {
                                    echo htmlspecialchars($todo_data['status']);
                                } else {
                                    echo 'Sin estado';
                                } ?>
                            </span>
                            <button class="btn btn-sm btn-danger float-end" data-bs-toggle="modal" data-bs-target="#deleteDoneTodoModal<?= $todo_data['id']; ?>">×</button>
                        </li>
                        <!-- Modal cambio de estado -->
                        <div class="modal fade" id="<?= $modal_id; ?>" tabindex="-1" aria-labelledby="<?= $modal_id; ?>Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="post" action="">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="<?= $modal_id; ?>Label">Cambiar estado del TODO</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="change_todo_status" value="1">
                                            <input type="hidden" name="todo_id" value="<?= htmlspecialchars($todo_data['id']); ?>">
                                            <?php $status_options = $myconfig['todo_status']; ?>
                                            <div class="mb-3">
                                                <label for="new_status_<?= $todo_data['id']; ?>" class="form-label">Selecciona nuevo estado:</label>
                                                <select class="form-select" id="new_status_<?= $todo_data['id']; ?>" name="new_status">
                                                    <?php foreach ($status_options as $opt) { ?>
                                                        <option value="<?= htmlspecialchars($opt); ?>" <?= $opt == $todo_data['status'] ? ' selected' : '' ?>><?= htmlspecialchars($opt); ?></option>
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
                        <!-- Modal eliminar -->
                        <div class="modal fade" id="deleteDoneTodoModal<?= $todo_data['id']; ?>" tabindex="-1" aria-labelledby="deleteDoneTodoModal<?= $todo_data['id']; ?>Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="post" action="">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteDoneTodoModal<?= $todo_data['id']; ?>Label">Eliminar TODO</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Estás seguro de que quieres eliminar este TODO?</p>
                                            <input type="hidden" name="delete_todo" value="1">
                                            <input type="hidden" name="todo_id" value="<?= htmlspecialchars($todo_data['id']); ?>">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php }
                } else { ?>
                    <li class="list-group-item">No hay TODOs terminados.</li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>

<!-- Modal añadir TODO -->
<div class="modal fade" id="addTodoModalCourse" tabindex="-1" aria-labelledby="addTodoModalCourseLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTodoModalCourseLabel">Añadir TODO</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="add_todo" value="1">
                    <input type="hidden" name="todo_course" value="<?= htmlspecialchars($course); ?>">
                    <div class="mb-3">
                        <label for="todo_name_course" class="form-label">Descripción *</label>
                        <textarea class="form-control" id="todo_name_course" name="todo_name" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="todo_link_course" class="form-label">Enlace (opcional)</label>
                        <input type="url" class="form-control" id="todo_link_course" name="todo_link">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Añadir</button>
                </div>
            </form>
        </div>
    </div>
</div>
