<?php include $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/templates/header.php'; ?>

<h1 class="text-center">Editar mi configuración</h1>
<div class="row justify-content-center">
    <div class="col-12 col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="post">
                    <?php foreach ($myconfig_new as $key => $value) {
                            if($key!='migrated_pending_done_tasks') { ?>
                        <div class="mb-3">
                            <label class="form-label" for="<?= $key ?>">
                                <?= ucfirst(str_replace('_', ' ', $key)) ?>
                            </label>
                            <input type="text" class="form-control" id="<?= $key ?>" name="<?= $key ?>" value="<?= htmlspecialchars(implode(',', $value)) ?>">
                        </div>
                    <?php } } ?>
                    <div class="d-grid">
                        <button type="submit" name="UpdateMyConfig" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card">
                <?php if (!empty($msg)) { ?>
                <div class="card-body">
                    <h5 class="card-title mb-4 text-center">Resultado</h5>
                    <div class="alert alert-success"> <?= htmlspecialchars($msg) ?> </div>
                </div>
                <?php } ?>
            <div class="card-body">
                <h5 class="card-title mb-4 text-center">Información</h5>
                <p>Separa por coma.</p>
                <p>No elimines el estado de Pendiente de de tareas o no te aparecerá nada en la caja de Tareas pendientes</p>
                <p>Para las columnas (Colx) tienes disponible; tasks, units, resources, tests, notes, calendar</p>
            </div>
        </div>
    </div>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/templates/footer.php'; ?>