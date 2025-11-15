/**
 * Ejemplo de respuesta del API mejorado
 * /calendar-api.php?year=2025&month=11
 */

// ANTES (sin información de tareas):
{
    "success": true,
    "year": 2025,
    "month": 11,
    "events": [
        {
            "UID": "8735@campus.digitechfp.com",
            "SUMMARY": "Tarea 1 - Instalación de SGBD a las 23:59",
            "DTSTART": "20251022T215900Z",
            "COURSE": "ASGBD"
        }
    ]
}

// AHORA (con información de tareas):
{
    "success": true,
    "year": 2025,
    "month": 11,
    "events": [
        {
            "UID": "8735@campus.digitechfp.com",
            "SUMMARY": "Tarea 1 - Instalación de SGBD a las 23:59",
            "DTSTART": "20251022T215900Z",
            "COURSE": "ASGBD",
            "TASK_STATUS": "Pendiente",        // ⭐ NUEVO
            "TASK_TYPE": "Obligatoria",         // ⭐ NUEVO
            "TASK_ID": "8735",                  // ⭐ NUEVO
            "TASK_END": "22-10-2025 23:59"     // ⭐ NUEVO
        }
    ]
}

/**
 * Visualización en el calendario
 */

// Evento SIN relación con tarea:
// ┌─────────────────────────────┐
// │ 15:30                      │
// │ [TE] Clase de Inglés       │
// └─────────────────────────────┘

// Evento CON tarea PENDIENTE:
// ┌─────────────────────────────┐
// │ 23:59                  ⚠️  │
// │ [ASGBD] Tarea 1 - Ins...   │
// └─────────────────────────────┘
// (Fondo amarillo claro)

// Evento CON tarea COMPLETADA:
// ┌─────────────────────────────┐
// │ 23:59                  ✅  │
// │ [ASO] Tarea 2 - Access...  │
// └─────────────────────────────┘
// (Fondo verde claro)

// Evento CON tarea EN PROGRESO:
// ┌─────────────────────────────┐
// │ 23:59                  🔄  │
// │ [IAW] Tarea 3 - Vistas...  │
// └─────────────────────────────┘
// (Fondo azul claro)
