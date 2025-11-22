/**
 * Vista de calendario mensual
 * Muestra eventos del calendar.json en formato mensual
 */

class CalendarView {
    constructor(containerId, apiUrl = '/calendar-api.php', coursesData = null) {
        this.container = document.getElementById(containerId);
        this.apiUrl = apiUrl;
        this.coursesData = coursesData; // Datos de courses con estados
        this.currentDate = new Date();
        this.events = [];
        this.meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                      'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        this.diasSemana = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
        
        this.init();
    }

    async init() {
        await this.loadEvents();
        this.render();
    }

    async loadEvents() {
        try {
            const year = this.currentDate.getFullYear();
            const month = this.currentDate.getMonth() + 1;
            const response = await fetch(`${this.apiUrl}?year=${year}&month=${month}`);
            const data = await response.json();
            this.events = data.events || [];
            
            // Enriquecer eventos con información de tareas si tenemos coursesData
            if (this.coursesData) {
                this.enrichEventsWithTaskStatus();
            }
            
            // Debug: mostrar cuántos eventos tienen información de tarea
            const eventsWithTasks = this.events.filter(e => e.TASK_STATUS);
            if (eventsWithTasks.length > 0) {
                console.log(`📋 Eventos con información de tarea: ${eventsWithTasks.length}/${this.events.length}`);
                console.log('Eventos con tareas:', eventsWithTasks.map(e => ({
                    summary: e.SUMMARY,
                    status: e.TASK_STATUS,
                    type: e.TASK_TYPE
                })));
            } else {
                console.log(`⚠️ No se encontraron eventos con información de tarea (Total eventos: ${this.events.length})`);
            }
        } catch (error) {
            console.error('Error cargando eventos:', error);
            this.events = [];
        }
    }

    enrichEventsWithTaskStatus() {
        this.events.forEach(event => {
            if (!event.COURSE || !event.SUMMARY) return;
            
            const courseCode = event.COURSE;
            const courseData = this.coursesData[courseCode];
            
            if (!courseData || !courseData.tasks) return;
            
            // Normalizar nombre del evento
            let eventName = event.SUMMARY;
            eventName = eventName.replace(/ a las \d{2}:\d{2}( horas?)?$/i, '');
            eventName = eventName.replace(/^Vencimiento de /i, '');
            eventName = eventName.trim();
            
            // Buscar coincidencia con alguna tarea
            for (const [taskId, taskData] of Object.entries(courseData.tasks)) {
                const taskName = taskData.name || '';
                
                // Comparar nombres
                let match = false;
                if (taskName) {
                    // Método 1: Coincidencia exacta
                    if (eventName.toLowerCase() === taskName.toLowerCase()) {
                        match = true;
                    }
                    // Método 2: Uno contiene al otro (al menos 80% del tamaño)
                    else if (eventName.toLowerCase().includes(taskName.toLowerCase()) || 
                             taskName.toLowerCase().includes(eventName.toLowerCase())) {
                        match = true;
                    }
                }
                
                if (match) {
                    event.TASK_STATUS = taskData.status || 'Sin estado';
                    event.TASK_TYPE = taskData.type || '';
                    event.TASK_ID = taskId;
                    event.TASK_END = taskData.end || '';
                    break; // Solo la primera coincidencia
                }
            }
        });
    }

    parseEventDate(dateStr) {
        // Formato: 20251001T153000Z (UTC)
        if (!dateStr || dateStr.length < 8) return null;
        
        const year = parseInt(dateStr.substring(0, 4));
        const month = parseInt(dateStr.substring(4, 6)) - 1;
        const day = parseInt(dateStr.substring(6, 8));
        
        if (dateStr.length >= 15 && dateStr.endsWith('Z')) {
            // Es UTC, usar Date.UTC para crear la fecha correctamente
            const hours = parseInt(dateStr.substring(9, 11));
            const minutes = parseInt(dateStr.substring(11, 13));
            const seconds = dateStr.length >= 17 ? parseInt(dateStr.substring(13, 15)) : 0;
            
            // Crear fecha UTC y luego convertir a fecha local
            let date = new Date(Date.UTC(year, month, day, hours, minutes, seconds));
            
            // Si la hora UTC es 00:00 (medianoche), mostrar como día anterior a las 23:59
            if (hours === 0 && minutes === 0 && seconds === 0) {
                date = new Date(date.getTime() - 60000); // Restar 1 minuto (día anterior 23:59)
            }
            
            return date;
        }
        
        return new Date(year, month, day);
    }

    getEventsForDay(year, month, day) {
        return this.events.filter(event => {
            const eventDate = this.parseEventDate(event.DTSTART);
            if (!eventDate) return false;
            
            return eventDate.getFullYear() === year &&
                   eventDate.getMonth() === month &&
                   eventDate.getDate() === day;
        });
    }

    getDaysInMonth(year, month) {
        return new Date(year, month + 1, 0).getDate();
    }

    getFirstDayOfMonth(year, month) {
        // getDay() devuelve 0=Domingo, 1=Lunes, etc.
        // Ajustamos para que 0=Lunes, 1=Martes, ..., 6=Domingo
        const day = new Date(year, month, 1).getDay();
        return day === 0 ? 6 : day - 1;
    }

    prevMonth() {
        this.currentDate.setMonth(this.currentDate.getMonth() - 1);
        this.init();
    }

    nextMonth() {
        this.currentDate.setMonth(this.currentDate.getMonth() + 1);
        this.init();
    }

    goToToday() {
        this.currentDate = new Date();
        this.init();
    }

    formatTime(dateStr) {
        // Primero verificar si es medianoche UTC (00:00:00Z)
        if (dateStr && dateStr.length >= 15 && dateStr.endsWith('Z')) {
            const hours = parseInt(dateStr.substring(9, 11));
            const minutes = parseInt(dateStr.substring(11, 13));
            const seconds = dateStr.substring(13, 15) ? parseInt(dateStr.substring(13, 15)) : 0;
            
            // Si es medianoche UTC, mostrar 23:59
            if (hours === 0 && minutes === 0 && seconds === 0) {
                return '23:59';
            }
        }
        
        const date = this.parseEventDate(dateStr);
        if (!date) return '';
        
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        return `${hours}:${minutes}`;
    }

    getCourseColor(course) {
        const colors = {
        'SRI': '#006266',
        'ASO': '#00b894',
        'CB': '#00cec9',
        'ASGBD': '#c44569',
        'SAD': '#fd79a8',
        'IAW': '#EAB543',
        'TE': '#FEA47F',
        'IPE': '#341f97',
        'DAPS': '#0984e3',
        'SAPS': '#74b9ff'
        };
        return colors[course] || '#b2bec3';
    }

    render() {
        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();
        const daysInMonth = this.getDaysInMonth(year, month);
        const firstDay = this.getFirstDayOfMonth(year, month);
        
        const today = new Date();
        const isToday = (day) => {
            return today.getFullYear() === year &&
                   today.getMonth() === month &&
                   today.getDate() === day;
        };

        let html = `
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <button class="btn btn-light btn-sm" onclick="calendar.prevMonth()">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <h5 class="mb-0">${this.meses[month]} ${year}</h5>
                        <div>
                            <button class="btn btn-light btn-sm me-2" onclick="calendar.goToToday()">Hoy</button>
                            <button class="btn btn-light btn-sm" onclick="calendar.nextMonth()">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="calendar-grid">
                        <div class="calendar-header">
                            ${this.diasSemana.map(dia => `<div class="calendar-day-name">${dia}</div>`).join('')}
                        </div>
                        <div class="calendar-days">
        `;

        // Días vacíos antes del primer día del mes
        for (let i = 0; i < firstDay; i++) {
            html += '<div class="calendar-day empty"></div>';
        }

        // Días del mes
        for (let day = 1; day <= daysInMonth; day++) {
            const dayEvents = this.getEventsForDay(year, month, day);
            const todayClass = isToday(day) ? ' today' : '';
            const hasEventsClass = dayEvents.length > 0 ? ' has-events' : '';
            
            html += `
                <div class="calendar-day${todayClass}${hasEventsClass}">
                    <div class="day-number">${day}</div>
                    <div class="day-events">
            `;
            
            // Mostrar todos los eventos del día
            dayEvents.forEach(event => {
                const time = this.formatTime(event.DTSTART);
                const course = event.COURSE || '';
                const courseColor = this.getCourseColor(course);
                const summary = event.SUMMARY || '';
                
                // Extraer solo el título sin la hora
                let title = summary.replace(/\s+a las \d{2}:\d{2}( horas?)?$/i, '');
                title = title.length > 30 ? title.substring(0, 27) + '...' : title;
                
                // Indicadores de estado de tarea
                let statusIcon = '';
                let statusClass = '';
                let taskLink = '';
                
                if (event.TASK_STATUS) {
                    if (event.TASK_STATUS === 'Pendiente') {
                        statusIcon = '<i class="fas fa-exclamation-circle text-warning ms-1" title="Tarea pendiente" style="font-size: 0.75rem;"></i>';
                        statusClass = ' task-pending';
                    } else if (event.TASK_STATUS === 'Entregada' || event.TASK_STATUS === 'Completada') {
                        statusIcon = '<i class="fas fa-check-circle text-success ms-1" title="Tarea completada" style="font-size: 0.75rem;"></i>';
                        statusClass = ' task-completed';
                    } else if (event.TASK_STATUS === 'En progreso') {
                        statusIcon = '<i class="fas fa-spinner text-info ms-1" title="En progreso" style="font-size: 0.75rem;"></i>';
                        statusClass = ' task-progress';
                    }
                    
                    // Generar enlace a la tarea en el campus
                    if (event.TASK_ID) {
                        taskLink = `https://campus.digitechfp.com/mod/assign/view.php?id=${event.TASK_ID}`;
                    }
                }
                
                // Si hay enlace a tarea, hacer el evento clicable
                const eventContent = taskLink 
                    ? `<a href="${taskLink}" target="_blank" style="text-decoration: none; color: inherit; display: block;">
                        <small class="text-muted">${time}</small>
                        ${course ? `<span class="badge" style="background-color: ${courseColor}; font-size: 0.65rem;">${course}</span>` : ''}
                        <div class="event-title">${title}${statusIcon}</div>
                       </a>`
                    : `<small class="text-muted">${time}</small>
                       ${course ? `<span class="badge" style="background-color: ${courseColor}; font-size: 0.65rem;">${course}</span>` : ''}
                       <div class="event-title">${title}${statusIcon}</div>`;
                
                html += `
                    <div class="event-item${statusClass}" style="border-left: 3px solid ${courseColor};" 
                         data-bs-toggle="tooltip" title="${summary}${event.TASK_STATUS ? ' - Estado: ' + event.TASK_STATUS : ''}${taskLink ? ' - Click para ir al campus' : ''}">
                        ${eventContent}
                    </div>
                `;
            });
            
            html += `
                    </div>
                </div>
            `;
        }

        html += `
                        </div>
                    </div>
                </div>
            </div>
        `;

        this.container.innerHTML = html;
        
        // Activar tooltips de Bootstrap
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
}

// Variable global para acceder al calendario
let calendar;

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('custom-calendar')) {
        // Pasar coursesData si está disponible
        const coursesData = window.coursesData || null;
        calendar = new CalendarView('custom-calendar', '/calendar-api.php', coursesData);
    }
});
