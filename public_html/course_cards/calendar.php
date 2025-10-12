<?php // Card: Calendario
?>
<div class="card mb-3 border-0" id="calendar">
    <div class="agenda-list">
        <?php
        // Mostrar las próximas 6 clases de este curso
        $clases = [];
        if (!empty($calendar_events)) {
            foreach ($calendar_events as $ev) {
                if (isset($ev['SUMMARY']) && stripos($ev['SUMMARY'], 'Clase') === 0 && isset($ev['DTSTART']) && isset($ev['COURSE']) && $ev['COURSE'] === $course) {
                    $clases[] = $ev;
                }
            }
            // Ordenar por DTSTART (fecha de inicio)
            usort($clases, function ($a, $b) {
                return strcmp($a['DTSTART'], $b['DTSTART']);
            });
            // Filtrar solo las próximas 6 clases a partir de hoy
            $now = date('Ymd\THis\Z');
            $clases = array_filter($clases, function ($ev) use ($now) {
                return $ev['DTSTART'] >= $now;
            });
            $clases = array_slice($clases, 0, 6);
            if (!empty($clases)) {
                echo '<div class="d-flex flex-column gap-2">';
                foreach ($clases as $clase) {
                    // Formatear fecha: día grande, mes y día semana pequeño
                    $date_big = $date_small = '';
                    if (isset($clase['DTSTART'])) {
                        $dt = null;
                        if (preg_match('/^\d{8}T\d{6}Z$/', $clase['DTSTART'])) {
                            $dt = DateTime::createFromFormat('Ymd\THis\Z', $clase['DTSTART'], new DateTimeZone('UTC'));
                            if ($dt) $dt->setTimezone(new DateTimeZone('Europe/Madrid'));
                        }
                        if ($dt) {
                            $meses = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
                            $dias = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
                            $date_big = $dt->format('j');
                            $mes = $meses[(int)$dt->format('n') - 1];
                            $dow = $dias[(int)$dt->format('w')];
                            $date_small = "$mes, $dow";
                        } else {
                            $date_big = htmlspecialchars($clase['DTSTART']);
                        }
                    }
                    $summary = htmlspecialchars($clase['SUMMARY']);
                    // Calcular color de fondo según la fecha
                    $fondo = '#f8fafc';
                    $clase_fondo = '';
                    if (isset($dt) && $dt) {
                        $hoy = (new DateTime('now', new DateTimeZone('Europe/Madrid')))->setTime(0,0,0);
                        $fecha_ev = clone $dt; $fecha_ev->setTime(0,0,0);
                        $diff = (int)$hoy->diff($fecha_ev)->format('%R%a');
                        if ($diff === 0) {
                            $clase_fondo = ' bg-danger text-bg-danger';
                        } elseif ($diff > 0 && $diff <= 3) {
                            $clase_fondo = ' bg-warning text-bg-warning';
                        }
                    }
                    echo '<div class="agenda-event card shadow-sm border-0'.$clase_fondo.'" style="background: #f8fafc;">';
                    echo '<div class="card-body d-flex align-items-center">';
                    echo '<div class="me-3 text-center" style="min-width:60px;">';
                    echo '<div class="agenda-date text-primary">';
                    echo '<span class="agenda-date-big text-primary-emphasis">' . $date_big . '</span><br>';
                    if ($date_small) echo '<span class="agenda-date-small">' . $date_small . '</span>';
                    echo '</div>';
                    echo '<div><i class="bi bi-calendar-event" style="font-size:1.5em;color:#0d6efd;"></i></div>';
                    echo '</div>';
                    echo '<div class="flex-grow-1">';
                    echo '<div class="agenda-title fw-semibold" style="font-size:1.1em;">' . $summary . '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                echo '</div>';
            } else {
                echo '<div class="alert alert-secondary">No hay clases próximas.</div>';
            }
        } else {
            echo '<div class="alert alert-warning">Hay problemas con el archivo calendar.json</div>';
        }
        ?>
    </div>
</div>
