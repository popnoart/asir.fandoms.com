</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>

<!-- Modal de sincronización de calendario -->
<div class="modal fade" id="calendarSyncModal" tabindex="-1" aria-labelledby="calendarSyncModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="calendarSyncModalLabel">Sincronizar calendario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p>Para sincronizar el calendario en tu app favorita, usa esta URL:</p>
                <div class="input-group mb-2">
                    <input type="text" class="form-control" id="calendarSyncUrl" value="https://asir.fandoms.com/calendar.php" readonly>
                    <button class="btn btn-outline-secondary" type="button" onclick="navigator.clipboard.writeText(document.getElementById('calendarSyncUrl').value)">Copiar</button>
                </div>
                <small class="text-muted">Puedes añadirla en Google Calendar, Outlook, iOS, Android, etc. como calendario por URL.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Estados -->
<div class="modal fade" id="estadosModal" tabindex="-1" aria-labelledby="estadosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="estadosModalLabel">Editar Estados</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="estadosForm">
                    <div class="alert mt-2 d-none" id="estadosMsg"></div>
                    <div id="estadosFormFields">
                        <!-- Los campos se cargarán por AJAX -->
                        <div class="text-center text-muted"><span class="spinner-border spinner-border-sm"></span> Cargando...</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" form="estadosForm" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var estadosModal = document.getElementById('estadosModal');
        var estadosFormFields = document.getElementById('estadosFormFields');
        var estadosMsg = document.getElementById('estadosMsg');

        estadosModal.addEventListener('show.bs.modal', function() {
            estadosFormFields.innerHTML = '<div class="text-center text-muted"><span class="spinner-border spinner-border-sm"></span> Cargando...</div>';
            estadosMsg.classList.add('d-none');
            // Cargar los campos actuales por AJAX
            fetch('/editar_config.php')
                .then(r => r.text())
                .then(html => {
                    // Extraer solo el formulario
                    var temp = document.createElement('div');
                    temp.innerHTML = html;
                    var form = temp.querySelector('form');
                    if (form) {
                        estadosFormFields.innerHTML = form.innerHTML;
                    } else {
                        estadosFormFields.innerHTML = '<div class="alert alert-danger">No se pudo cargar el formulario.</div>';
                    }
                });
        });
    });
</script>
</body>

</html>