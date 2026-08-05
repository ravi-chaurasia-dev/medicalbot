<div class="panel-card">
    <div class="panel-header mb-4">
        <div>
            <h4>Symptom history</h4>
            <p class="text-muted">Review previous symptom reports and analysis outcomes.</p>
        </div>
    </div>

    <?php if (empty($reports)): ?>
        <div class="alert alert-secondary">No symptom reports have been recorded yet.</div>
    <?php else: ?>
        <?php foreach ($reports as $report): ?>
            <div class="panel-card mb-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5>Report from <?= htmlspecialchars((string) $report['created_at'], ENT_QUOTES, 'UTF-8') ?></h5>
                        <p class="text-muted mb-0">Risk: <?= htmlspecialchars($report['risk_level'], ENT_QUOTES, 'UTF-8') ?> — Confidence: <?= htmlspecialchars((string) $report['confidence'], ENT_QUOTES, 'UTF-8') ?>%</p>
                    </div>
                    <?php if ($report['risk_level'] === 'high'): ?>
                        <span class="badge bg-danger">Emergency</span>
                    <?php endif; ?>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <strong>Symptoms</strong>
                        <p><?= htmlspecialchars(implode(', ', $report['symptoms']), ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Duration</strong>
                        <p><?= htmlspecialchars($report['duration'], ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Medical history</strong>
                        <p><?= htmlspecialchars($report['medical_history'], ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Family history</strong>
                        <p><?= htmlspecialchars($report['family_history'], ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                </div>

                <div class="mt-3">
                    <strong>Possible conditions:</strong>
                    <p><?= htmlspecialchars(implode(', ', $report['conditions']), ENT_QUOTES, 'UTF-8') ?></p>
                    <strong>Suggested tests:</strong>
                    <p><?= htmlspecialchars($report['suggested_tests'], ENT_QUOTES, 'UTF-8') ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
