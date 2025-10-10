<?php
// calendar.php: exporta calendar.json como .ics
header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename="calendar.ics"');

function format_ics_date($date) {
    // Espera formato Ymd\THis\Z o Ymd\THis
    if (preg_match('/^\d{8}T\d{6}Z?$/', $date)) {
        return $date;
    }
    // Si es timestamp o fecha simple, intentar convertir
    if (is_numeric($date)) {
        return gmdate('Ymd\THis\Z', (int)$date);
    }
    $dt = strtotime($date);
    if ($dt !== false) {
        return gmdate('Ymd\THis\Z', $dt);
    }
    return $date;
}

$path = __DIR__ . '/data/calendar.json';
$events = file_exists($path) ? json_decode(file_get_contents($path), true) : [];

echo "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//asir.fandoms.com//calendar//ES\r\nCALSCALE:GREGORIAN\r\n";
foreach ($events as $ev) {
    if (empty($ev['DTSTART']) || empty($ev['SUMMARY'])) continue;
    $uid = !empty($ev['UID']) ? $ev['UID'] : md5($ev['SUMMARY'].$ev['DTSTART']);
    echo "BEGIN:VEVENT\r\n";
    echo "UID:" . $uid . "\r\n";
    echo "SUMMARY:" . str_replace(["\r","\n"], ' ', $ev['SUMMARY']) . "\r\n";
    echo "DTSTART:" . format_ics_date($ev['DTSTART']) . "\r\n";
    if (!empty($ev['DTEND'])) echo "DTEND:" . format_ics_date($ev['DTEND']) . "\r\n";
    if (!empty($ev['DESCRIPTION'])) echo "DESCRIPTION:" . str_replace(["\r","\n"], ' ', $ev['DESCRIPTION']) . "\r\n";
    if (!empty($ev['LOCATION'])) echo "LOCATION:" . str_replace(["\r","\n"], ' ', $ev['LOCATION']) . "\r\n";
    if (!empty($ev['LAST-MODIFIED'])) echo "LAST-MODIFIED:" . format_ics_date($ev['LAST-MODIFIED']) . "\r\n";
    echo "END:VEVENT\r\n";
}
echo "END:VCALENDAR\r\n";
?>