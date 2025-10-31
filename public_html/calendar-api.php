<?php
/**
 * API para obtener eventos del calendario por mes
 * Retorna eventos en formato JSON para el calendario mensual
 */

header('Content-Type: application/json; charset=utf-8');

// Obtener parámetros
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');

// Validar parámetros
if ($year < 2020 || $year > 2030) {
    $year = date('Y');
}
if ($month < 1 || $month > 12) {
    $month = date('n');
}

// Cargar eventos del archivo calendar.json
$calendar_path = __DIR__ . '/data/calendar.json';
$events = [];

if (file_exists($calendar_path)) {
    $json_content = file_get_contents($calendar_path);
    $all_events = json_decode($json_content, true);
    
    if (is_array($all_events)) {
        // Filtrar eventos del mes solicitado
        foreach ($all_events as $event) {
            if (empty($event['DTSTART'])) continue;
            
            // Parsear fecha DTSTART (formato: 20251001T153000Z)
            $dtstart = $event['DTSTART'];
            if (preg_match('/^(\d{4})(\d{2})(\d{2})/', $dtstart, $matches)) {
                $event_year = (int)$matches[1];
                $event_month = (int)$matches[2];
                
                // Solo incluir eventos del mes solicitado
                if ($event_year === $year && $event_month === $month) {
                    $events[] = $event;
                }
            }
        }
        
        // Ordenar eventos por fecha
        usort($events, function($a, $b) {
            return strcmp($a['DTSTART'], $b['DTSTART']);
        });
    }
}

// Retornar respuesta JSON
echo json_encode([
    'success' => true,
    'year' => $year,
    'month' => $month,
    'events' => $events,
    'count' => count($events)
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>
