<div class="panel-card">
    <div class="panel-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4>Lab Reports</h4>
            <p class="text-muted">Upload, preview, and review your lab report analyses.</p>
        </div>
        <div>
            <a href="/lab-reports" class="btn btn-outline-secondary">Refresh</a>
        </div>
    </div>

    <div class="panel-card mb-4 p-4 upload-panel">
        <div class="row g-3">
            <div class="col-lg-6">
                <form method="get" action="/lab-reports" class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" value="<?= htmlspecialchars($filters['search'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="File name or summary">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Risk level</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            <option value="low" <?= (($filters['status'] ?? '') === 'low') ? 'selected' : '' ?>>Low</option>
                            <option value="medium" <?= (($filters['status'] ?? '') === 'medium') ? 'selected' : '' ?>>Medium</option>
                            <option value="high" <?= (($filters['status'] ?? '') === 'high') ? 'selected' : '' ?>>High</option>
                        </select>
                    </div>
                    <div class="col-md-12 d-grid">
                        <button type="submit" class="btn btn-outline-secondary">Filter reports</button>
                    </div>
                </form>
            </div>

            <div class="col-lg-6">
                <form method="post" action="/lab-reports/upload" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                    <?= csrf_field() ?>
                    <div class="col-md-12">
                        <label class="form-label">Lab report file</label>
                        <input type="file" name="lab_report" class="form-control" accept=".pdf,.png,.jpg,.jpeg" required>
                    </div>
                    <div class="col-md-12 d-grid">
                        <button type="submit" class="btn btn-primary">Upload report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if (empty($reports)): ?>
        <div class="alert alert-secondary">No lab reports found. Upload a report to get started.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>File</th>
                        <th>Risk</th>
                        <th>Summary</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reports as $report): ?>
                        <tr>
                            <td><?= htmlspecialchars((string) $report['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($report['original_file_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <?php if ($report['risk_level'] === 'high'): ?>
                                    <span class="badge bg-danger">High</span>
                                <?php elseif ($report['risk_level'] === 'medium'): ?>
                                    <span class="badge bg-warning text-dark">Medium</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Low</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars(substr($report['report_summary'], 0, 90), ENT_QUOTES, 'UTF-8') ?>...</td>
                            <td>
                                <a href="/lab-reports/view?id=<?= htmlspecialchars((string) $report['id'], ENT_QUOTES, 'UTF-8') ?>" class="btn btn-sm btn-outline-primary">View</a>
                                <a href="/lab-reports/download?id=<?= htmlspecialchars((string) $report['id'], ENT_QUOTES, 'UTF-8') ?>" class="btn btn-sm btn-outline-secondary">Download</a>
                                <form method="post" action="/lab-reports/delete" class="d-inline-block" style="margin:0;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= htmlspecialchars((string) $report['id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this report?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
