<div class="panel-card">
    <div class="panel-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4>Report Management</h4>
            <p class="text-muted mb-0">Review and export lab and symptom reports across the platform.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="/admin/reports/export-csv" class="btn btn-outline-primary">Export CSV</a>
            <a href="/admin/reports/export-pdf" class="btn btn-outline-secondary">Export PDF</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="panel-card p-4">
                <h6>Recent lab reports</h6>
                <?php if (empty($labReports)): ?>
                    <div class="alert alert-secondary">No lab reports available.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>File</th>
                                    <th>Risk</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($labReports, 0, 8) as $report): ?>
                                    <tr>
                                        <td><?= htmlspecialchars((string) $report['id'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars((string) $report['user_id'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($report['original_file_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($report['risk_level'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars((string) $report['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="panel-card p-4">
                <h6>Recent symptom reports</h6>
                <?php if (empty($symptomReports)): ?>
                    <div class="alert alert-secondary">No symptom reports available.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Risk</th>
                                    <th>Symptoms</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($symptomReports, 0, 8) as $report): ?>
                                    <tr>
                                        <td><?= htmlspecialchars((string) $report['id'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars((string) $report['user_id'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($report['risk_level'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars(implode(', ', array_slice($report['symptoms'] ?? [], 0, 3)), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars((string) $report['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
