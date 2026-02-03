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

function parse_ics_events($ics_path) {
	if (!file_exists($ics_path)) return [];
	$ics = file_get_contents($ics_path);
	$lines = preg_split('/\r\n|\n|\r/', $ics);
	$events = [];
	$event = [];
	$in_event = false;
	$prev_key = null;
	foreach ($lines as $line) {
		// Soporte para folded lines (líneas que empiezan con espacio/tab)
		if ((strpos($line, ' ') === 0 || strpos($line, "\t") === 0) && $prev_key !== null && $in_event) {
			// Continuación de la línea anterior
			$event[$prev_key] .= ltrim($line);
			continue;
		}
		if (trim($line) === 'BEGIN:VEVENT') {
			$in_event = true;
			$event = [];
			$prev_key = null;
		} elseif (trim($line) === 'END:VEVENT') {
			$in_event = false;
			$events[] = $event;
			$prev_key = null;
		} elseif ($in_event) {
			if (strpos($line, ':') !== false) {
				list($key, $value) = explode(':', $line, 2);
				$event[$key] = $value;
				$prev_key = $key;
			}
		}
	}
	return $events;
}

// Ahora calendar.json es el formato principal
function load_calendar_json($json_path) {
	if (!file_exists($json_path)) return [];
	$data = json_decode(file_get_contents($json_path), true);
	return is_array($data) ? $data : [];
}


// Inicializar variable para evitar warning
$message_result = $message_result ?? null;

// Descargar icalexport.ics desde la URL y guardarlo localmente
if (isset($_GET['download']) AND $_GET['download']=='ok') {
	$url = 'https://campus.digitechfp.com/calendar/export_execute.php?userid=1157&authtoken=324b744d6ad143f0374834c2065fb54add01e36b&preset_what=all&preset_time=custom';
	$ics = file_get_contents($url);
	if($ics === false) {
		$message_result = 'Error al descargar icalexport.ics desde la URL.';
	}else{
		$message_result = 'Archivo icalexport.ics descargado correctamente.';
	}
	file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/data/icalexport.ics', $ics);
}
$icalexport_events = parse_ics_events($_SERVER['DOCUMENT_ROOT'] . '/data/icalexport.ics');
$calendarics_events = load_calendar_json($_SERVER['DOCUMENT_ROOT'] . '/data/calendar.json');

// Indexar por UID para comparación
function index_by_uid($events) {
	$out = [];
	foreach ($events as $ev) {
		if (isset($ev['UID'])) {
			$out[$ev['UID']] = $ev;
		}
	}
	return $out;
}
$icalexport_by_uid = index_by_uid($icalexport_events);
$calendarics_by_uid = index_by_uid($calendarics_events);

// UIDs únicos
$all_uids = array_unique(array_merge(array_keys($icalexport_by_uid), array_keys($calendarics_by_uid)));
sort($all_uids);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_missing'])) {
	// Buscar eventos que están en icalexport.ics pero no en calendar.json
	$missing = [];
	foreach ($icalexport_by_uid as $uid => $ev) {
		if (!isset($calendarics_by_uid[$uid])) {
			
			// Determinar COURSE usando $equivalence y CATEGORIES
			$course_value = null;
			if (isset($ev['CATEGORIES'])) {
				foreach ($equivalence as $cat_prefix => $eq_val) {
					if (strpos($ev['CATEGORIES'], $cat_prefix) === 0) {
						$course_value = $eq_val;
						break;
					}
				}
			}
			$ev['COURSE'] = $course_value;
			$missing[] = $ev;
		}
	}
	if ($missing) {
		// Añadir los eventos que faltan a calendar.json
		$calendar_json_path = $_SERVER['DOCUMENT_ROOT'] . '/data/calendar.json';
		$calendarics_events = load_calendar_json($calendar_json_path);
		foreach ($missing as $ev) {
			$calendarics_events[] = $ev;
		}
		file_put_contents($calendar_json_path, json_encode($calendarics_events, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
		$message_result = count($missing) . ' evento(s) añadidos a calendar.json.';
		header('Location: ' . $_SERVER['REQUEST_URI']);
		exit;
	} else {
		$message_result = 'No hay eventos nuevos que añadir.';
	}
}
// Eliminar evento individual de calendar.json
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_uid'])) {
	$uid_to_delete = $_POST['delete_uid'];
	$calendar_json_path = $_SERVER['DOCUMENT_ROOT'] . '/data/calendar.json';
	$calendarics_events = load_calendar_json($calendar_json_path);
	$calendarics_events = array_values(array_filter($calendarics_events, function($ev) use ($uid_to_delete) {
		return !isset($ev['UID']) || $ev['UID'] !== $uid_to_delete;
	}));
	file_put_contents($calendar_json_path, json_encode($calendarics_events, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
	$message_result = 'Evento eliminado de calendar.json.';
	header('Location: ' . $_SERVER['REQUEST_URI']);
	exit;
}

// Actualizar evento individual si modificado en icalexport.ics
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_uid'])) {
	$uid_to_update = $_POST['update_uid'];
	$calendar_json_path = $_SERVER['DOCUMENT_ROOT'] . '/data/calendar.json';
	$calendarics_events = load_calendar_json($calendar_json_path);
	$icalexport_event = $icalexport_by_uid[$uid_to_update] ?? null;
	if ($icalexport_event) {
		// Mantener COURSE si existe en calendar.json
		foreach ($calendarics_events as &$ev) {
			if (isset($ev['UID']) && $ev['UID'] === $uid_to_update) {
				if (isset($ev['COURSE'])) {
					$icalexport_event['COURSE'] = $ev['COURSE'];
				}
				$ev = $icalexport_event;
				break;
			}
		}
		unset($ev);
		file_put_contents($calendar_json_path, json_encode($calendarics_events, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
		$message_result = 'Evento actualizado desde icalexport.ics.';
		header('Location: ' . $_SERVER['REQUEST_URI']);
		exit;
	}
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Importación de calendario de Digitech</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
	<h1>Importación de calendario de Digitech</h1>
	<p class="lead">Compara y añade eventos de <code>icalexport.ics</code> a <code>calendar.json</code>. <a href="/tools/import.php?download=ok">Descargar</a>.</p>
	<?php if ($message_result): ?>
		<div class="alert alert-info"> <?php echo htmlspecialchars($message_result); ?> </div>
	<?php endif; ?>
	<form method="post" class="mb-3">
		<button type="submit" name="add_missing" value="1" class="btn btn-primary">Añadir nuevos eventos</button>
	</form>

	<div class="row mb-3">
		<div class="col-md-6">
			<h5><code>icalexport.ics</code></h5>
			<div class="list-group">
			<?php
			foreach ($all_uids as $uid):
				$ev = $icalexport_by_uid[$uid] ?? null;
				$ev_json = $calendarics_by_uid[$uid] ?? null;
				$both = ($ev && $ev_json);
				// 1. Ocultar si coinciden completamente (todos los campos relevantes)
				if ($both && $ev == $ev_json) continue;
				// 2. Ocultar si solo está en calendar.json, su fecha fin ya pasó y no está en icalexport.ics
				if (!$ev && $ev_json) {
					$fecha_fin = $ev_json['DTEND'] ?? null;
					$es_pasado = false;
					if ($fecha_fin) {
						$fecha_ev = preg_replace('/[^0-9]/', '', substr($fecha_fin,0,8));
						$hoy = (new DateTime('now', new DateTimeZone('Europe/Madrid')))->format('Ymd');
						$es_pasado = ($fecha_ev < $hoy);
					}
					if ($es_pasado) continue;
				}
			?>
				<div class="list-group-item<?php
					$modificado = $both && (($ev['LAST-MODIFIED'] ?? null) !== ($ev_json['LAST-MODIFIED'] ?? null));
					$alt_ev = $ev ?: ($ev_json ?? []);
					$fecha_inicio = $alt_ev['DTSTART'] ?? null;
					$es_pasado = false;
					if ($fecha_inicio) {
						$fecha_ev = preg_replace('/[^0-9]/', '', substr($fecha_inicio,0,8));
						$hoy = (new DateTime('now', new DateTimeZone('Europe/Madrid')))->format('Ymd');
						$es_pasado = ($fecha_ev < $hoy);
					}
					if ($modificado) echo ' list-group-item-warning';
					elseif ($ev && !isset($calendarics_by_uid[$uid])) echo ' list-group-item-success';
					elseif (!$ev && isset($calendarics_by_uid[$uid])) echo ' list-group-item-danger';
				?>">
					<?php $alt_ev = $ev ?: ($ev_json ?? []); ?>
					<strong><?php echo htmlspecialchars($alt_ev['SUMMARY'] ?? '(Sin resumen)'); ?></strong><br>
					UID: <?php echo htmlspecialchars($uid); ?><br>
					<?php if (!empty($alt_ev['CATEGORIES'])){?>
						<?php echo htmlspecialchars($alt_ev['CATEGORIES']); ?><br>
					<?php } ?>
					Fecha fin: <?php echo isset($alt_ev['DTEND']) ? format_ics_date_madrid($alt_ev['DTEND']) : ''; ?><br>
					<?php if ($modificado): ?>
						<span class="badge bg-warning text-dark">Modificado (LAST-MODIFIED distinto)</span>
					<?php elseif ($ev && !isset($calendarics_by_uid[$uid])): ?>
						<span class="badge bg-success">Solo en icalexport.ics</span>
					<?php elseif (!$ev && isset($calendarics_by_uid[$uid])): ?>
						<span class="badge bg-danger">Solo en calendar.json</span>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
			</div>
		</div>
		<div class="col-md-6">
			<h5><code>calendar.json</code></h5>
			<div class="list-group">
			<?php
			foreach ($all_uids as $uid):
				$ev = $calendarics_by_uid[$uid] ?? null;
				$ev_ics = $icalexport_by_uid[$uid] ?? null;
				$both = ($ev && $ev_ics);
				// 1. Ocultar si coinciden completamente (todos los campos relevantes)
				if ($both && $ev == $ev_ics) continue;
				// 2. Ocultar si solo está en calendar.json, su fecha fin ya pasó y no está en icalexport.ics
				if ($ev && !$ev_ics) {
					$fecha_fin = $ev['DTEND'] ?? null;
					$es_pasado = false;
					if ($fecha_fin) {
						$fecha_ev = preg_replace('/[^0-9]/', '', substr($fecha_fin,0,8));
						$hoy = (new DateTime('now', new DateTimeZone('Europe/Madrid')))->format('Ymd');
						$es_pasado = ($fecha_ev < $hoy);
					}
					if ($es_pasado) continue;
				}
			?>
				<div class="list-group-item<?php
					$modificado = $both && (($ev['LAST-MODIFIED'] ?? null) !== ($ev_ics['LAST-MODIFIED'] ?? null));
					$alt_ev = $ev ?: ($ev_ics ?? []);
					$fecha_inicio = $alt_ev['DTSTART'] ?? null;
					$es_pasado = false;
					if ($fecha_inicio) {
						$fecha_ev = preg_replace('/[^0-9]/', '', substr($fecha_inicio,0,8));
						$hoy = (new DateTime('now', new DateTimeZone('Europe/Madrid')))->format('Ymd');
						$es_pasado = ($fecha_ev < $hoy);
					}
					if ($modificado) echo ' list-group-item-warning';
					elseif ($ev && !isset($icalexport_by_uid[$uid])) echo ' list-group-item-success';
					elseif (!$ev && isset($icalexport_by_uid[$uid])) echo ' list-group-item-danger';
				?>">
					<?php $alt_ev = $ev ?: ($ev_ics ?? []); ?>
					<strong><?php echo htmlspecialchars($alt_ev['SUMMARY'] ?? '(Sin resumen)'); ?></strong><br>
					UID: <?php echo htmlspecialchars($uid); ?><br>
					<?php if (!empty($alt_ev['CATEGORIES'])){?>
						<?php echo htmlspecialchars($alt_ev['CATEGORIES']); ?><br>
					<?php } ?>
					Fecha fin: <?php echo isset($alt_ev['DTEND']) ? format_ics_date_madrid($alt_ev['DTEND']) : ''; ?><br>
					<?php if ($modificado): ?>
						<span class="badge bg-warning text-dark">Modificado (LAST-MODIFIED distinto)</span>
						<form method="post" style="display:inline">
								<input type="hidden" name="update_uid" value="<?php echo htmlspecialchars($uid); ?>">
								<button type="submit" class="btn btn-sm btn-warning ms-2">Actualizar</button>
							</form>
					<?php endif; ?>
					<?php if ($ev && !isset($icalexport_by_uid[$uid])): ?>
						<span class="badge bg-success">Solo en calendar.json</span>
						<form method="post" style="display:inline">
							<input type="hidden" name="delete_uid" value="<?php echo htmlspecialchars($uid); ?>">
							<button type="submit" class="btn btn-sm btn-danger ms-2">Eliminar</button>
						</form>
					<?php endif; ?>
					<?php if (!$ev && isset($icalexport_by_uid[$uid])): ?>
						<span class="badge bg-danger">Solo en icalexport.ics</span>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
			</div>
		</div>
	</div>
	<div class="mt-4">
		<h5>Resumen</h5>
		<ul>
			   <li><span class="badge bg-success">Solo en icalexport.ics</span>: Evento existe solo en icalexport.ics (verde)</li>
			   <li><span class="badge bg-danger">Solo en calendar.json</span>: Evento existe solo en calendar.json (rojo)</li>
		</ul>
	</div>
</div>

</body>
</html>
