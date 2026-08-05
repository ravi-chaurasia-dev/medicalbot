<div class="dashboard-grid">
    <div class="stats-row row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card primary">
                <span class="label">Registered Users</span>
                <strong><?= htmlspecialchars((string) ($metrics['users'] ?? 0), ENT_QUOTES, 'UTF-8') ?></strong>
                <small>Active platform users</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card success">
                <span class="label">Hospitals</span>
                <strong><?= htmlspecialchars((string) ($metrics['hospitals'] ?? 0), ENT_QUOTES, 'UTF-8') ?></strong>
                <small>Available clinical sites</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card warning">
                <span class="label">Recent Reports</span>
                <strong><?= htmlspecialchars((string) (($metrics['lab_reports'] ?? 0) + ($metrics['symptom_reports'] ?? 0)), ENT_QUOTES, 'UTF-8') ?></strong>
                <small>Lab & symptom reports</small>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="panel-card chart-panel p-4">
                <div class="panel-header d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5>Operational snapshot</h5>
                        <p class="text-muted mb-0">Recent system activity and clinical workflow summary.</p>
                    </div>
                </div>

                <div class="row gy-3">
                    <div class="col-md-4">
                        <div class="metric-box">
                            <span>Active chats</span>
                            <strong><?= htmlspecialchars((string) ($metrics['chats'] ?? 0), ENT_QUOTES, 'UTF-8') ?></strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="metric-box">
                            <span>Total lab reports</span>
                            <strong><?= htmlspecialchars((string) ($metrics['lab_reports'] ?? 0), ENT_QUOTES, 'UTF-8') ?></strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="metric-box">
                            <span>Symptom assessments</span>
                            <strong><?= htmlspecialchars((string) ($metrics['symptom_reports'] ?? 0), ENT_QUOTES, 'UTF-8') ?></strong>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <h6 class="mb-3">Latest audit events</h6>
                    <?php if (empty($recentLogs)): ?>
                        <div class="alert alert-secondary">No audit logs available yet.</div>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($recentLogs as $log): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong><?= htmlspecialchars($log['action'], ENT_QUOTES, 'UTF-8') ?></strong>
                                        <div class="text-muted small">User: <?= htmlspecialchars($log['user_name'] ?? 'System', ENT_QUOTES, 'UTF-8') ?> • <?= htmlspecialchars((string) $log['created_at'], ENT_QUOTES, 'UTF-8') ?></div>
                                    </div>
                                    <span class="badge bg-secondary rounded-pill">ID <?= htmlspecialchars((string) $log['id'], ENT_QUOTES, 'UTF-8') ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="panel-card p-4">
                <div class="panel-header d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5>Admin quick actions</h5>
                        <p class="text-muted mb-0">Navigate management workstreams.</p>
                    </div>
                </div>
                <div class="list-group">
                    <a href="/admin/users" class="list-group-item list-group-item-action">User management</a>
                    <a href="/admin/hospitals" class="list-group-item list-group-item-action">Hospital records</a>
                    <a href="/admin/doctors" class="list-group-item list-group-item-action">Doctor directory</a>
                    <a href="/admin/reports" class="list-group-item list-group-item-action">Report exports</a>
                </div>
            </div>
        </div>
    </div>
</div>
