<div class="panel-card">
    <div class="panel-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4>Compare Lab Reports</h4>
            <p class="text-muted">Side-by-side view of two selected lab report analyses.</p>
        </div>
        <div>
            <a href="/lab-reports" class="btn btn-outline-primary">Back to reports</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="panel-card p-4">
                <h5><?= htmlspecialchars($leftReport['original_file_name'], ENT_QUOTES, 'UTF-8') ?></h5>
                <p><strong>Uploaded:</strong> <?= htmlspecialchars((string) $leftReport['created_at'], ENT_QUOTES, 'UTF-8') ?></p>
                <p><?= nl2br(htmlspecialchars($leftReport['report_summary'], ENT_QUOTES, 'UTF-8')) ?></p>
                <h6>Values</h6>
                <ul class="list-group">
                    <?php foreach ($leftValues as $value): ?>
                        <li class="list-group-item">
                            <strong><?= htmlspecialchars($value['analyte'], ENT_QUOTES, 'UTF-8') ?>:</strong>
                            <?= htmlspecialchars((string) $value['value'], ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars($value['unit'], ENT_QUOTES, 'UTF-8') ?>
                            <span class="text-muted">(<?= htmlspecialchars($value['status'], ENT_QUOTES, 'UTF-8') ?>)</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="panel-card p-4">
                <h5><?= htmlspecialchars($rightReport['original_file_name'], ENT_QUOTES, 'UTF-8') ?></h5>
                <p><strong>Uploaded:</strong> <?= htmlspecialchars((string) $rightReport['created_at'], ENT_QUOTES, 'UTF-8') ?></p>
                <p><?= nl2br(htmlspecialchars($rightReport['report_summary'], ENT_QUOTES, 'UTF-8')) ?></p>
                <h6>Values</h6>
                <ul class="list-group">
                    <?php foreach ($rightValues as $value): ?>
                        <li class="list-group-item">
                            <strong><?= htmlspecialchars($value['analyte'], ENT_QUOTES, 'UTF-8') ?>:</strong>
                            <?= htmlspecialchars((string) $value['value'], ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars($value['unit'], ENT_QUOTES, 'UTF-8') ?>
                            <span class="text-muted">(<?= htmlspecialchars($value['status'], ENT_QUOTES, 'UTF-8') ?>)</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
