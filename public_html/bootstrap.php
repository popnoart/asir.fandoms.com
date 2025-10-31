<?php
define('ROOT', dirname(__DIR__));

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['account'])) {
    header('Location: /login.php');
    exit;
}

//////////LOAD ALL CONFIGS & STATES\\\\\\\\\\
$personal_path=ROOT.'/storage/data/accounts/'.$_SESSION['account'].'/';
// Cargar configuración
$config = include $_SERVER['DOCUMENT_ROOT'] . '/config.php';
// Cargar courses
$courses_path = $_SERVER['DOCUMENT_ROOT'] . '/data/courses.json';
$all_courses = json_decode(file_get_contents($courses_path), true);
// Cargar calendario
$calendar_path = $_SERVER['DOCUMENT_ROOT'] . '/data/calendar.json';
$calendar_events = json_decode(file_get_contents($calendar_path), true);
// Personales
$myconfig_path = $personal_path.'config.json';
$myconfig = json_decode(file_get_contents($myconfig_path), true);

$states_path = $personal_path.'states.json';
$all_states = json_decode(file_get_contents($states_path), true);


//////////FUNCTIONS\\\\\\\\\\
// Obtiene el color del curso según su código
function get_course_color($course_code) {
    $colors = [
        'SRI' => '#006266',
        'ASO' => '#00b894',
        'CB' => '#00cec9',
        'ASGBD' => '#c44569',
        'SAD' => '#fd79a8',
        'IAW' => '#EAB543',
        'TE' => '#FEA47F',
        'IPE' => '#0984e3',
        'DAPS' => '#6c5ce7',
        'SAPS' => '#D980FA'
    ];
    return isset($colors[$course_code]) ? $colors[$course_code] : '#b2bec3';
}

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
function sort_by_end($a, $b)
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
    // Tests
    if (!empty($course_data['tests']) && is_array($course_data['tests'])) {
        foreach ($course_data['tests'] as $test_key => $test_data) {
            if (isset($test_key) && !isset($all_states['tests'][$test_key])) {
                $all_states['tests'][$test_key] = 'Pendiente';
                $all_courses[$course_key]['tests'][$test_key]['status'] = 'Pendiente';
            } else {
                $all_courses[$course_key]['tests'][$test_key]['status'] = $all_states['tests'][$test_key];
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
    if (!empty($_GET['quiz'])) {
        $quiz = $_GET['quiz'];
        $quiz_data = $course_data['tests'][$quiz] ?? null;
    }
} else {
    $course = null;
    $course_data = null;
}

//////////REEMPLAZAR Y LIMPIAR PENDING_TASKS Y DONE_TASKS EN $myconfig SOLO UNA VEZ\\\\\\
if (is_array($myconfig) && empty($myconfig['migrated_pending_done_tasks'])) {
    foreach (['col1', 'col2', 'col3'] as $col) {
        if (isset($myconfig[$col]) && is_array($myconfig[$col])) {
            // Reemplazar 'pending_tasks' por 'tasks'
            $myconfig[$col] = array_map(function($v) {
                return $v === 'pending_tasks' ? 'tasks' : $v;
            }, $myconfig[$col]);
            // Eliminar 'done_tasks'
            $myconfig[$col] = array_values(array_filter($myconfig[$col], function($v) {
                return $v !== 'done_tasks';
            }));
        }
    }
    $myconfig['migrated_pending_done_tasks'] = true;
    file_put_contents($myconfig_path, json_encode($myconfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}


//////////TASKS\\\\\\\\\\

// Tareas pendientes y terminadas de la asignatura actual
if (!empty($course)) {
    $pending_tasks_course = [];
    $done_tasks_course = [];
    foreach ($course_data['tasks'] as $task => $task_data) {
        if (isset($task_data['status']) && $task_data['status'] === 'Pendiente') {
            $pending_tasks_course[] = $task_data + ['course' => $course,'id' => $task];
        } else {
            $done_tasks_course[] = $task_data + ['course' => $course,'id' => $task];
        }
    }
    usort($pending_tasks_course, 'sort_by_end');
    usort($done_tasks_course, 'sort_by_end');
}
// Tareas pendientes globales
else{
    $pending_tasks = [];
    foreach ($all_courses as $course_key => $course_data) {
        if (!empty($course_data['tasks']) && is_array($course_data['tasks'])) {
            foreach ($course_data['tasks'] as $task => $task_data) {
                if (isset($task_data['status']) && $task_data['status'] === 'Pendiente') {
                    $pending_tasks[] = $task_data + ['course' => $course_key,'id' => $task];
                } else {
                    $done_tasks_course[] = $task_data + ['course' => $course_key,'id' => $task];
                }
            }
        }
    }
    usort($pending_tasks, 'sort_by_end');
}



//////////TESTS\\\\\\\\\\

// Tests pendientes y terminados de la asignatura actual
if (!empty($course)) {
    $pending_tests_course = [];
    $done_tests_course = [];
    foreach ($course_data['tests'] as $test => $test_data) {
        if (isset($test_data['status']) && $test_data['status'] === 'Pendiente') {
            $pending_tests_course[] = $test_data + ['course' => $course,'id' => $test];
        } else {
            $done_tests_course[] = $test_data + ['course' => $course,'id' => $test];
        }
    }
    usort($pending_tests_course, 'sort_by_end');
    usort($done_tests_course, 'sort_by_end');
}
// Tests pendientes globales
else{
    $pending_tests = [];
    foreach ($all_courses as $course_key => $course_data) {
        if (!empty($course_data['tests']) && is_array($course_data['tests'])) {
            foreach ($course_data['tests'] as $test => $test_data) {
                if (isset($test_data['status']) && $test_data['status'] === 'Pendiente') {
                    $pending_tests[] = $test_data + ['course' => $course_key,'id' => $test];
                } else {
                    $done_tests[] = $test_data + ['course' => $course_key,'id' => $test];
                }
            }
        }
    }
    usort($pending_tests, 'sort_by_end');
}

//////////MYCONFIG\\\\\\\\\\
$myconfig_empty = [
    'units_status' => [],
    'resources_status' => [],
    'tasks_status' => [],
    'tests_status' => [],
    'notes_status' => [],
    'col1' => [],
    'col2' => [],
    'col3' => []
];

if (is_array($myconfig)) {
    $myconfig_new = array_merge($myconfig_empty, $myconfig);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['UpdateMyConfig'])) {
    foreach ($myconfig_new as $key => $value) {
        if (isset($_POST[$key])) {
            $myconfig_new[$key] = array_map('trim', explode(',', $_POST[$key]));
        }
    }
    file_put_contents($myconfig_path, json_encode($myconfig_new, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $myconfig = $myconfig_new;
}


//////////STATUS\\\\\\\\\\
// Procesar cambio de estado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_status_id'], $_POST['new_status'])) {
    $uid = $_POST['change_status_id'];
    $type = $_POST['change_status_type'];
    $new_status = $_POST['new_status'];
    $all_states[$type][$uid] = $new_status;
    file_put_contents($states_path, json_encode($all_states, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}
