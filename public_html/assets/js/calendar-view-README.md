# Sistema de Indicadores de Estado de Tareas en el Calendario

## Descripción
El calendario ahora muestra indicadores visuales que relacionan los eventos del `calendar.json` con las tareas de `courses.json`, permitiendo ver de un vistazo el estado de las tareas directamente en el calendario mensual.

## Cómo funciona

### 1. Backend (calendar-api.php)
- Cuando se cargan los eventos del mes, el API también carga `courses.json`
- Para cada evento que tenga un código de curso (`COURSE`), busca si hay tareas relacionadas
- Compara el nombre del evento con los nombres de las tareas usando:
  - Coincidencia parcial (si un nombre contiene al otro)
  - Similitud de texto (70% o más)
- Si encuentra una coincidencia, añade al evento:
  - `TASK_STATUS`: Estado de la tarea (Pendiente, Entregada, etc.)
  - `TASK_TYPE`: Tipo de tarea (Obligatoria, Complementaria)
  - `TASK_ID`: ID de la tarea en Moodle
  - `TASK_END`: Fecha de fin de la tarea

### 2. Frontend (calendar-view.js)
- Al renderizar cada evento, verifica si tiene información de tarea (`TASK_STATUS`)
- Añade iconos y estilos según el estado:

#### Iconos de estado:
- **⚠️ Pendiente**: Icono de exclamación amarillo (`fa-exclamation-circle`)
- **✅ Completada/Entregada**: Icono de check verde (`fa-check-circle`)
- **🔄 En progreso**: Icono de spinner azul (`fa-spinner`)

### 3. Estilos visuales (calendar-view.css)
Los eventos con tareas tienen colores de fondo distintivos:

- **Pendiente**: Fondo amarillo claro (`#fff3cd`)
- **Completada**: Fondo verde claro (`#d1e7dd`)
- **En progreso**: Fondo azul claro (`#cfe2ff`)

Todos los eventos con tareas tienen un borde izquierdo más grueso (4px) para mayor visibilidad.

## Tooltip mejorado
Al pasar el mouse sobre un evento con tarea, el tooltip muestra:
- Nombre completo del evento
- Estado actual de la tarea

## Ventajas
✅ Visión rápida del estado de las tareas en el calendario
✅ No requiere cambios en la estructura de datos existente
✅ Compatible con el sistema de estados actual
✅ Funciona automáticamente con las tareas ya existentes
✅ Responsive: se adapta a pantallas móviles

## Posibles estados
Según tu sistema actual, los estados pueden ser:
- Pendiente
- En progreso
- Entregada
- Completada
- (Cualquier otro estado definido en tu configuración)

## Personalización
Para añadir más estados o cambiar iconos, edita en `calendar-view.js`:
```javascript
if (event.TASK_STATUS === 'TuEstado') {
    statusIcon = '<i class="fas fa-tu-icono text-color ms-1" ...></i>';
    statusClass = ' task-custom-class';
}
```

Y añade los estilos correspondientes en `calendar-view.css`.
