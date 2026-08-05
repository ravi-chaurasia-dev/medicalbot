<div class="panel-card">
    <div class="panel-header mb-4">
        <div>
            <h4>Symptom analysis result</h4>
            <p class="text-muted">Review your symptom report and suggested next steps.</p>
        </div>
        <?php if ($analysis['risk_level'] === 'high'): ?>
            <span class="badge bg-danger">Emergency</span>
        <?php elseif ($analysis['risk_level'] === 'medium'): ?>
            <span class="badge bg-warning text-dark">Moderate risk</span>
        <?php else: ?>
            <span class="badge bg-success">Low risk</span>
        <?php endif; ?>
    </div>

    <div class="row gy-4">
        <div class="col-lg-8">
            <div class="panel-card p-4 mb-4">
                <h5>AI Reasoning</h5>
                <p><?= nl2br(htmlspecialchars($analysis['explanation'], ENT_QUOTES, 'UTF-8')) ?></p>
            </div>

            <div class="panel-card p-4 mb-4">
                <h5>Possible conditions</h5>
                <ul class="list-group">
                    <?php foreach ($analysis['conditions'] as $condition): ?>
                        <li class="list-group-item"><?= htmlspecialchars($condition, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="panel-card p-4">
                <h5>Suggested diagnostic tests</h5>
                <p><?= htmlspecialchars($analysis['suggested_tests'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="panel-card p-4 mb-4 emergency-card">
                <h5>Emergency warning</h5>
                <p><?= nl2br(htmlspecialchars($analysis['emergency_warning'], ENT_QUOTES, 'UTF-8')) ?></p>
            </div>

            <div class="panel-card p-4 mb-4">
                <h5>Confidence</h5>
                <div class="progress mb-2">
                    <div class="progress-bar" role="progressbar" style="width: <?= htmlspecialchars((string) $analysis['confidence'], ENT_QUOTES, 'UTF-8') ?>%;" aria-valuenow="<?= htmlspecialchars((string) $analysis['confidence'], ENT_QUOTES, 'UTF-8') ?>" aria-valuemin="0" aria-valuemax="100"><?= htmlspecialchars((string) $analysis['confidence'], ENT_QUOTES, 'UTF-8') ?>%</div>
                </div>
            </div>

            <div class="panel-card p-4">
                <h5>Follow-up questions</h5>
                <ul class="list-group list-group-flush">
                    <?php foreach ($analysis['follow_up_questions'] as $question): ?>
                        <li class="list-group-item"><?= htmlspecialchars($question, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
