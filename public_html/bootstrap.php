<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['account'])) {
    header('Location: /login.php');
    exit;
}

//////////LOAD ALL CONFIGS & STATES\\\\\\\\\\
$personal_path=__DIR__ . '/../storage/data/accounts/'.$_SESSION['account'].'/';
// Cargar configuración
$config = include $_SERVER['DOCUMENT_ROOT'] . '/config.php';
// Cargar courses
$courses_path = $_SERVER['DOCUMENT_ROOT'] . '/data/courses.json';
$all_courses = json_decode(file_get_contents($courses_path), true);
// Cargar calendario
$calendar_path = $_SERVER['DOCUMENT_ROOT'] . '/data/calendar.json';
$calendar_events = json_decode(file_get_contents($calendar_path), true);
// Personales
$myconfig_path = $personal_path.'/config.json';
$myconfig = json_decode(file_get_contents($myconfig_path), true);

$states_path = $personal_path.'/states.json';
$all_states = json_decode(file_get_contents($states_path), true);


//////////FUNCTIONS\\\\\\\\\\
// Formatea fecha ICS a dd-mm-yyyy H:m en horario de Madrid
function format_ics_date_madrid($ics_date)
{
    if (preg_match('/^\d{8}T\d{6}Z$/', $ics_date)) {
        $dt = DateTime::createFromFormat('Ymd\THis\Z', $ics_date, new DateTimeZone('UTC'));
        if ($dt) {
            $dt->setTimezone(new DateTimeZone('Europe/Madrid'));
            return $dt->format('d-m-Y H:i');
        }
    }
    return htmlspecialchars($ics_date);
}

// Función para ordenar por fecha de finalización ascendente
function sort_tasks_by_end($a, $b)
{
    $a_end = strtotime(str_replace('/', '-', $a['end']));
    $b_end = strtotime(str_replace('/', '-', $b['end']));
    if ($a_end == $b_end) return 0;
    return ($a_end < $b_end) ? -1 : 1;
}

//////////COURSES\\\\\\\\\\

// Sincronizar y cargar estados
foreach ($all_courses as $course_key => $course_data) {
    // Tasks
    if (!empty($course_data['tasks']) && is_array($course_data['tasks'])) {
        foreach ($course_data['tasks'] as $task_key => $task_data) {
            if (isset($task_key) && !isset($all_states['tasks'][$task_key])) {
                $all_states['tasks'][$task_key] = 'Pendiente';
                $all_courses[$course_key]['tasks'][$task_key]['status'] = 'Pendiente';
            } else {
                $all_courses[$course_key]['tasks'][$task_key]['status'] = $all_states['tasks'][$task_key];
            }
        }
    }
    // Units
    if (!empty($course_data['units']) && is_array($course_data['units'])) {
        foreach ($course_data['units'] as $unit_key => $unit_data) {
            if (isset($unit_key) && !isset($all_states['units'][$unit_key])) {
                $all_states['units'][$unit_key] = 'Pendiente';
                $all_courses[$course_key]['units'][$unit_key]['status'] = 'No empezado';
            } else {
                $all_courses[$course_key]['units'][$unit_key]['status'] = $all_states['units'][$unit_key];
            }
        }
    }
    // Notes
    if (!empty($course_data['notes']) && is_array($course_data['notes'])) {
        foreach ($course_data['notes'] as $note_key => $note_data) {
            if (isset($note_key) && !isset($all_states['notes'][$note_key])) {
                $all_states['notes'][$note_key] = 'Pendiente';
                $all_courses[$course_key]['notes'][$note_key]['status'] = 'Pendiente';
            } else {
                $all_courses[$course_key]['notes'][$note_key]['status'] = $all_states['notes'][$note_key];
            }
        }
    }
}
// Guardar cambios en states.json tras sincronizar
file_put_contents($states_path, json_encode($all_states, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

//Asignatura o portada
if (!empty($_GET['course'])) {
    $course = $_GET['course'];
    $course_data = $all_courses[$course] ?? null;
} else {
    $course = null;
    $course_data = null;
}


//////////TASKS\\\\\\\\\\

// Tareas pendientes y terminadas de la asignatura actual
if (!empty($course)) {
    $pending_tasks_course = [];
    $done_tasks_course = [];
    foreach ($course_data['tasks'] as $task) {
        if (isset($task['status']) && $task['status'] === 'Pendiente') {
            $pending_tasks_course[] = $task + ['course' => $course];
        } else {
            $done_tasks_course[] = $task + ['course' => $course];
        }
    }
    usort($pending_tasks_course, 'sort_tasks_by_end');
    usort($done_tasks_course, 'sort_tasks_by_end');
}
// Tareas pendientes globales
else{
    $pending_tasks = [];
    foreach ($all_courses as $course_key => $course_data) {
        if (!empty($course_data['tasks']) && is_array($course_data['tasks'])) {
            foreach ($course_data['tasks'] as $task) {
                if (isset($task['status']) && $task['status'] === 'Pendiente') {
                    $pending_tasks[] = $task + ['course' => $course_key];
                } else {
                    $done_tasks_course[] = $task + ['course' => $course_key];
                }
            }
        }
    }
    usort($pending_tasks, 'sort_tasks_by_end');
}

// Procesar cambio de estado de tarea
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_status_uid'], $_POST['new_status'])) {
    $uid = $_POST['change_status_uid'];
    $type = $_POST['change_status_type'];
    $new_status = $_POST['new_status'];
    $all_states[$type][$uid] = $new_status;
    file_put_contents($states_path, json_encode($all_states, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}
