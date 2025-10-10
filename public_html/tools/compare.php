<?php
// Mostrar errores para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['account'])) {
    header('Location: /login.php');
    exit;
}
if ($_SESSION['account']!='popnoart') {
    echo 'No tienes permiso para acceder a esta página '.$_SESSION['account'];
    exit;
}

$equivalence = [
	"ASIR - Sostenibilidad Aplicada al Sistema Productivo" => "SAPS",
	"ASIR - Digitalización Aplicada al Sistema Productivo" => "DAPS",
    "ASIR - CIberseguridad" => "CB",
    "ASIR - Itinerario Personal para la Empleabilidad II" => "IPE",
    "ASIR - Administración de sistemas gestores de base de datos" => "ASGBD",
    "ASIR - Implantación de aplicaciones web" => "IAW",
    "ASIR - Inglés profesional" => "TE",
    "ASIR - Servicios de red e Internet" => "SRI",
    "ASIR - Seguridad y alta disponibilidad" => "SAD",
    "ASIR - Administración de sistemas operativos" => "ASO"
];



// Botón para añadir nuevas tareas de calendar.json a tasks.json
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_new_tasks'])) {
	$data_path = $_SERVER['DOCUMENT_ROOT'] . '/data/tasks.json';
	$data = file_exists($data_path) ? json_decode(file_get_contents($data_path), true) : [];
	$status_path = $_SERVER['DOCUMENT_ROOT'] . '/data/tasks_status.json';
	$tasks_status = file_exists($status_path) ? json_decode(file_get_contents($status_path), true) : [];
	$calendar_json_path = $_SERVER['DOCUMENT_ROOT'] . '/data/calendar.json';
	$calendar_events = file_exists($calendar_json_path) ? json_decode(file_get_contents($calendar_json_path), true) : [];
	$added = 0;
	foreach ($calendar_events as $event) {
		if (isset($event['SUMMARY']) && stripos($event['SUMMARY'], 'Clase') !== 0 && isset($event['UID']) && isset($event['CATEGORIES'])) {
			$uid = $event['UID'];
			$uid_key = preg_replace('/@campus\\.digitechfp\\.com$/', '', $uid);
			if (!isset($data[$uid_key])) {
				$desc = isset($event['DESCRIPTION']) ? $event['DESCRIPTION'] : '';
				// Determinar el valor de 'course' usando $equivalence y CATEGORIES
				$course_val = '';
				if (isset($event['CATEGORIES'])) {
					foreach ($equivalence as $cat_key => $cat_val) {
						if (strpos($event['CATEGORIES'], $cat_key) === 0) {
							$course_val = $cat_val;
							break;
						}
					}
				}
				$data[$uid_key] = [
					'id' => null,
					'course' => $course_val,
					'start' => isset($event['DTSTART']) ? format_ics_date_madrid($event['DTSTART']) : '',
					'end' => isset($event['DTEND']) ? format_ics_date_madrid($event['DTEND']) : '',
					'name' => $event['SUMMARY'],
					'description' => $desc
				];
				// Añadir estado pendiente en tasks_status.json si no existe
				if (!isset($tasks_status[$uid_key])) {
					$tasks_status[$uid_key] = 'Pendiente';
				}
				$added++;
			}
		}
	}
	if ($added > 0) {
		file_put_contents($data_path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
		file_put_contents($status_path, json_encode($tasks_status, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
		
		// Incrementar versión de caché para forzar recarga
		$version_file = $_SERVER['DOCUMENT_ROOT'] . '/data/cache_version.txt';
		$version = 1;
		if (file_exists($version_file)) {
			$version = (int)file_get_contents($version_file);
		}
		$version++;
		file_put_contents($version_file, $version);
		
		$add_result = "$added tarea(s) añadidas a tasks.json.";
	} else {
		$add_result = "No hay tareas nuevas que añadir.";
	}
}


// Formatea fecha ICS a dd-mm-yyyy H:m en horario de Madrid
function format_ics_date_madrid($ics_date) {
    if (preg_match('/^\d{8}T\d{6}Z$/', $ics_date)) {
        $dt = DateTime::createFromFormat('Ymd\THis\Z', $ics_date, new DateTimeZone('UTC'));
        if ($dt) {
            $dt->setTimezone(new DateTimeZone('Europe/Madrid'));
            return $dt->format('d-m-Y H:i');
        }
    }
    return htmlspecialchars($ics_date);
}


// --- Comparación de eventos de calendar.json (no 'Clase') con tasks.json ---
$calendar_json_path = $_SERVER['DOCUMENT_ROOT'] . '/data/calendar.json';
$calendarics_events = file_exists($calendar_json_path) ? json_decode(file_get_contents($calendar_json_path), true) : [];

$data_tasks = [];
$data_tasks_by_uid = [];
if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/data/tasks.json')) {
    $data = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/data/tasks.json'), true);
    foreach ($data as $uid_key => $task) {
        $data_tasks[$uid_key] = $task;
        $data_tasks_by_uid[$uid_key] = [
            'task' => $task,
            'course' => isset($task['course']) ? $task['course'] : ''
        ];
    }
}

$calendar_events = [];
foreach ($calendarics_events as $event) {
    if (isset($event['SUMMARY']) && stripos($event['SUMMARY'], 'Clase') !== 0 && isset($event['UID'])) {
        $uid = $event['UID'];
        $uid_key = preg_replace('/@campus\\.digitechfp\\.com$/', '', $uid);
        $calendar_events[] = [
            'event' => $event,
            'uid_key' => $uid_key,
            'data_task' => isset($data_tasks_by_uid[$uid_key]) ? $data_tasks_by_uid[$uid_key] : null
        ];
    }
}



?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Importación de tareas de Digitech</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
	<h1>Importación de tareas de Digitech</h1>
	<p class="lead">Compara y añade tareas de <code>calendar.json</code> a <code>tasks.json</code>.</p>
	<form method="post" class="mb-3">
		<button type="submit" name="add_new_tasks" value="1" class="btn btn-primary">Añadir nuevas tareas</button>
	</form>
	<div class="row mb-3">
		<div class="col-md-6">
			<h5><code>calendar.json</code></h5>
			<div class="list-group">
			<?php foreach ($calendar_events as $pair): 
				$ev = $pair['event'];
				$uid_key = $pair['uid_key'];
			?>
				<div class="list-group-item<?php echo empty($pair['data_task']) ? ' list-group-item-danger' : ''; ?>">
					<strong><?php echo htmlspecialchars($ev['SUMMARY'] ?? ''); ?></strong><br>
					<?php if (!empty($ev['UID'])): ?>
						UID: <?php echo htmlspecialchars($ev['UID']); ?><br>
					<?php endif; ?>
					<?php if (!empty($ev['CATEGORIES'])): ?>
						<span class="text-muted"><?php echo htmlspecialchars($ev['CATEGORIES']); ?></span><br>
					<?php endif; ?>
					<?php if (!empty($ev['DTSTART'])): ?>
						Fecha inicio: <?php echo htmlspecialchars(format_ics_date_madrid($ev['DTSTART'])); ?><br>
					<?php endif; ?>
					<?php if (!empty($ev['DTEND'])): ?>
						Fecha fin: <?php echo htmlspecialchars(format_ics_date_madrid($ev['DTEND'])); ?><br>
					<?php endif; ?>
					<?php if (empty($pair['data_task'])): ?>
						<span class="badge bg-danger">No encontrada en tasks,json</span>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
			</div>
		</div>
		<div class="col-md-6">
			<h5><code>tasks.json</code></h5>
			<div class="list-group">
			<?php foreach ($calendar_events as $pair): 
				$ev = $pair['event'];
				$uid_key = $pair['uid_key'];
				$data_task = $pair['data_task'];
				if (!$data_task) {
					// Mostrar los datos del evento de calendar.json también aquí si no se encuentra en tasks,json
					?>
					<div class="list-group-item list-group-item-danger">
						<strong><?php echo htmlspecialchars($ev['SUMMARY'] ?? ''); ?></strong><br>
						<?php if (!empty($ev['UID'])): ?>
							UID: <?php echo htmlspecialchars($ev['UID']); ?><br>
						<?php endif; ?>
						<?php if (!empty($ev['CATEGORIES'])): ?>
							<span class="text-muted"><?php echo htmlspecialchars($ev['CATEGORIES']); ?></span><br>
						<?php endif; ?>
						<?php if (!empty($ev['DTSTART'])): ?>
							Fecha inicio: <?php echo htmlspecialchars(format_ics_date_madrid($ev['DTSTART'])); ?><br>
						<?php endif; ?>
						<?php if (!empty($ev['DTEND'])): ?>
							Fecha fin: <?php echo htmlspecialchars(format_ics_date_madrid($ev['DTEND'])); ?><br>
						<?php endif; ?>
						<span class="badge bg-danger">No encontrada en tasks,json</span>
					</div>
					<?php
					continue;
				}
				$task = $data_task['task'];
				$course = $data_task['course'];
			?>
				<div class="list-group-item">
					<strong><?php echo htmlspecialchars($task['name'] ?? ''); ?></strong><br>
					UID: <?php echo htmlspecialchars($uid_key); ?><br>
					Curso: <?php echo htmlspecialchars($course); ?><br>
					<?php if (!empty($task['start'])): ?>
						Fecha inicio: <?php echo htmlspecialchars($task['start']); ?><br>
					<?php endif; ?>
					<?php if (!empty($task['end'])): ?>
						Fecha fin: <?php echo htmlspecialchars($task['end']); ?><br>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
</body>
</html>
