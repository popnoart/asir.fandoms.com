<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php if (!empty($course)) {
                echo $course . ': ' . $course_data['name'];
            } else {
                echo 'ASIR online';
            } ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/calendar-view.css">

    <style>
        a {
            text-decoration: none !important;
        }

        .agenda-event {
            transition: box-shadow 0.2s;
        }

        .agenda-event:hover {
            box-shadow: 0 0 0.5rem #0d6efd33;
            background: #e9f5ff;
        }

        .agenda-date {
            letter-spacing: 0.5px;
        }

        .agenda-title {
            color: #222;
        }

        .agenda-date-big {
            font-size: 2.2em;
            line-height: 1;
        }

        .agenda-date-small {
            font-size: 1em;
            line-height: 1;
        }

    </style>
</head>

<body style="padding-top:75px;">

    <header class="fixed-top font-monospace">
        <nav class="navbar navbar-expand-lg bg-info rounded" data-bs-theme="light" aria-label="Clases">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">ASIR</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navCourses" aria-controls="navCourses" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
                <div class="collapse navbar-collapse" id="navCourses">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <?php foreach ($all_courses as $nav_key => $nav_data) { ?>
                            <li class="nav-item">
                                <a class="nav-link<?php if ($course === $nav_key) echo ' active'; ?>" href="/course.php?course=<?= $nav_key; ?>" data-bs-toggle="tooltip" data-bs-title="<?= $nav_data['name']; ?>"><?= $nav_key; ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                    <div class="justify-content-lg-end">
                        <a class="btn btn-outline-dark me-2" href="https://campus.digitechfp.com/" target="_blank">
                            <i class="fas fa-radiation"></i> Digitech
                        </a>
                        <a class="btn btn-outline-dark me-2" href="https://outlook.office.com/mail/" target="_blank">
                            <i class="fas fa-envelope"></i> Outlook
                        </a>
                        <a class="btn btn-outline-dark me-2" href="/account_config_update.php" target="_blank">
                            <i class="fas fa-sliders-h"></i> Conf.
                        </a>
                        <a class="btn btn-outline-dark me-2" href="#" data-bs-toggle="modal" data-bs-target="#calendarSyncModal">
                            <i class="fas fa-calendar-alt"></i> Sinc.
                        </a>
                        <?php if ($_SESSION['account'] == 'popnoart') { ?>
                            <a class="btn btn-outline-light me-2" href="https://asir.fandoms.test/tools/ocr.php" target="_blank">
                                <i class="fas fa-file-pdf"></i>
                            </a> 
                            <a class="btn btn-outline-light me-2" href="https://asir.fandoms.test/tools/tests.php" target="_blank">
                                <i class="fas fa-check-square"></i>
                            </a>
                            <a class="btn btn-outline-light me-2" href="/tools/import.php" target="_blank">
                                <i class="fas fa-calendar-alt"></i>
                            </a>
                            <a class="btn btn-outline-light me-2" href="/tools/invite.php" target="_blank">
                                <i class="fas fa-user-plus"></i>
                            </a>
                            <!--<a class="btn btn-outline-light me-2" href="/tools/compare.php" target="_blank">
                                <i class="fas fa-clipboard-list"></i> Tasks
                            </a>-->
                        <?php } ?>
                    </div>
                </div>
            </div>
        </nav>

    </header>

    <div class="container-fluid">