<?php include $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/templates/header.php'; ?>

<h1 class="text-center" id="<?= $course; ?>"><?= $course; ?>: <?= $course_data['name']; ?></h1>
<p class="lead text-center">Profesor: <a href="mailto:<?= isset($course_data['teacher_mail']) ? htmlspecialchars($course_data['teacher_mail']) : 'no disponible'; ?>"><?= htmlspecialchars($course_data['teacher']); ?></a></p>
<ul class="nav justify-content-center mb-3">
    <?php foreach ($config['sections'] as $section => $section_data) { ?>
        <li class="nav-item"><a class="nav-link text-info" href="<?= str_replace('[id]', $course_data['id'], $section_data['url']); ?>" target="_blank"><?= $section_data['name']; ?></a></li>
    <?php } ?>
</ul>

<div class="row g-4">
    <?php
    $columns = ['col1', 'col2', 'col3'];
    foreach ($columns as $i => $col) {
        echo '<div class="col-12 col-md-4">';
        if (isset($myconfig[$col]) && is_array($myconfig[$col])) {
            foreach ($myconfig[$col] as $card) {
                $card_file = $_SERVER['DOCUMENT_ROOT'] . '/course_cards/' . $card . '.php';
                if (file_exists($card_file)) {
                    include $card_file;
                }
            }
        }
        echo '</div>';
    }
    ?>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/templates/footer.php'; ?>