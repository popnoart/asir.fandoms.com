/**
 * Vista de calendario mensual
 * Muestra eventos del calendar.json en formato mensual
 */

class CalendarView {
    constructor(containerId, apiUrl = '/calendar-api.php') {
        this.container = document.getElementById(containerId);
        this.apiUrl = apiUrl;
        this.currentDate = new Date();
        this.events = [];
        this.meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                      'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        this.diasSemana = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
        
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
        } catch (error) {
            console.error('Error cargando eventos:', error);
            this.events = [];
        }
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
            return new Date(Date.UTC(year, month, day, hours, minutes, seconds));
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
        return new Date(year, month, 1).getDay();
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
        'IPE': '#0984e3',
        'DAPS': '#6c5ce7',
        'SAPS': '#D980FA'
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
                let title = summary.replace(/\s+a las \d{2}:\d{2}$/, '');
                title = title.length > 30 ? title.substring(0, 27) + '...' : title;
                
                html += `
                    <div class="event-item" style="border-left: 3px solid ${courseColor};" 
                         data-bs-toggle="tooltip" title="${summary}">
                        <small class="text-muted">${time}</small>
                        ${course ? `<span class="badge" style="background-color: ${courseColor}; font-size: 0.65rem;">${course}</span>` : ''}
                        <div class="event-title">${title}</div>
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
        calendar = new CalendarView('custom-calendar');
    }
});
