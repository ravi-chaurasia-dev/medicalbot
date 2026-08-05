<div class="panel-card">
    <div class="panel-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4>Lab Report Details</h4>
            <p class="text-muted">Detailed extraction and interpretation of your uploaded lab report.</p>
        </div>
        <div>
            <a href="/lab-reports/download?id=<?= htmlspecialchars((string) $report['id'], ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-secondary">Download PDF</a>
            <a href="/lab-reports" class="btn btn-outline-primary">Back to reports</a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="panel-card p-4 mb-4">
                <h5>Report summary</h5>
                <p><?= nl2br(htmlspecialchars($report['report_summary'], ENT_QUOTES, 'UTF-8')) ?></p>
                <h5>Explanation</h5>
                <p><?= nl2br(htmlspecialchars($report['explanation'], ENT_QUOTES, 'UTF-8')) ?></p>
                <h5>Recommendations</h5>
                <p><?= nl2br(htmlspecialchars($report['recommendations'], ENT_QUOTES, 'UTF-8')) ?></p>
            </div>

            <div class="panel-card p-4 mb-4">
                <h5>Extracted values</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Analyte</th>
                                <th>Value</th>
                                <th>Unit</th>
                                <th>Normal range</th>
                                <th>Status</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($values as $value): ?>
                                <tr class="<?= $value['status'] !== 'normal' ? 'table-warning' : '' ?>">
                                    <td><?= htmlspecialchars($value['analyte'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars((string) $value['value'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($value['unit'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($value['normal_range'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($value['status'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($value['note'], ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="panel-card p-4 mb-4 emergency-card">
                <h5>Risk level</h5>
                <?php if ($report['risk_level'] === 'high'): ?>
                    <span class="badge bg-danger">High</span>
                <?php elseif ($report['risk_level'] === 'medium'): ?>
                    <span class="badge bg-warning text-dark">Medium</span>
                <?php else: ?>
                    <span class="badge bg-success">Low</span>
                <?php endif; ?>
                <h5 class="mt-4">Preview</h5>
                <?php if (in_array(pathinfo($report['stored_file_path'], PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'], true)): ?>
                    <img src="<?= htmlspecialchars($report['stored_file_path'], ENT_QUOTES, 'UTF-8') ?>" class="img-fluid rounded" alt="Lab report preview">
                <?php else: ?>
                    <div class="alert alert-secondary">PDF preview is available via download.</div>
                <?php endif; ?>
            </div>

            <div class="panel-card p-4">
                <h5>Report metadata</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>File</strong> <?= htmlspecialchars($report['original_file_name'], ENT_QUOTES, 'UTF-8') ?></li>
                    <li class="list-group-item"><strong>Uploaded</strong> <?= htmlspecialchars((string) $report['created_at'], ENT_QUOTES, 'UTF-8') ?></li>
                    <li class="list-group-item"><strong>Type</strong> <?= htmlspecialchars($report['file_type'], ENT_QUOTES, 'UTF-8') ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>
