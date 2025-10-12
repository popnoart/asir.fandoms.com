<?php // Card: Recursos
?>
<div class="card mb-3" id="resources">
    <div class="card-body">
        <h5 class="card-title">Recursos</h5>
        <ul class="list-group list-group-flush">
            <?php foreach ($course_data['resources'] as $resource => $resource_data) {  ?>
                <li class="list-group-item">
                    <?php if (!empty($resource_data['link'])) { ?>
                        <a href="<?= $resource_data['link']; ?>" target="_blank"><?= htmlspecialchars($resource_data['name']); ?></a>
                    <?php } else { ?>
                        <?= htmlspecialchars($resource_data['name']); ?>
                    <?php } ?>
                    <?php if (!empty($resource_data['status'])) { ?>
                        <span class="badge bg-secondary">
                            <?php if (!empty($resource_data['status'])) {
                                echo htmlspecialchars($resource_data['status']);
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
