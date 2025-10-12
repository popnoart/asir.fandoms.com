<?php // Card: Tests
?>
<div class="card mb-3" id="tests">
    <div class="card-body">
        <h5 class="card-title">Tests</h5>
        <ul class="list-group list-group-flush">
            <?php foreach ($course_data['tests'] as $test => $test_data) {?>
                <li class="list-group-item">
                    <?php if (!empty($test_data['link'])) { ?>
                        <a href="<?= $test_data['link']; ?>" target="_blank"><?= htmlspecialchars($test_data['name']); ?></a>
                    <?php } else { ?>
                        <?= htmlspecialchars($test_data['name']); ?>
                    <?php } ?>
                    <?php if (!empty($test_data['status'])) { ?>
                        <span class="badge bg-secondary">
                            <?php if (!empty($test_data['status'])) {
                                echo htmlspecialchars($test_data['status']);
                            } else {
                                echo 'Sin estado';
                            } ?>
                        </span>
                    <?php } ?>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>
