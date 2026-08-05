<div class="dashboard-grid">
    <div class="stats-row row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card primary">
                <span class="label">Latest BP</span>
                <strong><?= $latestBloodPressure !== null ? htmlspecialchars($latestBloodPressure['systolic'] . '/' . $latestBloodPressure['diastolic'] . ' ' . ($latestBloodPressure['unit'] ?? 'mmHg'), ENT_QUOTES, 'UTF-8') : 'N/A' ?></strong>
                <small><?= $latestBloodPressure !== null ? htmlspecialchars((string) $latestBloodPressure['recorded_at'], ENT_QUOTES, 'UTF-8') : 'No records yet' ?></small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card success">
                <span class="label">Latest Weight</span>
                <strong><?= $latestWeight !== null ? htmlspecialchars((string) $latestWeight['value'] . ' ' . ($latestWeight['unit'] ?? 'kg'), ENT_QUOTES, 'UTF-8') : 'N/A' ?></strong>
                <small><?= $latestWeight !== null ? htmlspecialchars((string) $latestWeight['recorded_at'], ENT_QUOTES, 'UTF-8') : 'No records yet' ?></small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card warning">
                <span class="label">Latest Blood Sugar</span>
                <strong><?= $latestSugar !== null ? htmlspecialchars((string) $latestSugar['value'] . ' ' . ($latestSugar['unit'] ?? 'mg/dL'), ENT_QUOTES, 'UTF-8') : 'N/A' ?></strong>
                <small><?= $latestSugar !== null ? htmlspecialchars((string) $latestSugar['recorded_at'], ENT_QUOTES, 'UTF-8') : 'No records yet' ?></small>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="panel-card p-4">
                <div class="panel-header d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5>Health summary</h5>
                        <p class="text-muted mb-0">Track recent lab results, symptom assessments and clinical interactions.</p>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="metric-box">
                            <span>Recent lab reports</span>
                            <strong><?= htmlspecialchars((string) count($recentLabReports), ENT_QUOTES, 'UTF-8') ?></strong>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="metric-box">
                            <span>Recent symptom assessments</span>
                            <strong><?= htmlspecialchars((string) count($recentSymptomReports), ENT_QUOTES, 'UTF-8') ?></strong>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <h6 class="mb-3">Recent activity</h6>
                    <?php if (empty($recentMetrics) && empty($recentChats)): ?>
                        <div class="alert alert-secondary">No recent clinical activity available. Add readings or start a conversation in the AI Assistant.</div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($recentMetrics as $metric): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <strong><?= htmlspecialchars(ucwords(str_replace('_', ' ', $metric['metric_type'])), ENT_QUOTES, 'UTF-8') ?></strong>
                                        <span class="text-muted small"><?= htmlspecialchars((string) $metric['recorded_at'], ENT_QUOTES, 'UTF-8') ?></span>
                                    </div>
                                    <div class="text-muted small">
                                        <?= htmlspecialchars((string) ($metric['value'] ?? ($metric['systolic'] . '/' . $metric['diastolic'])), ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars((string) ($metric['unit'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <?php foreach ($recentChats as $chat): ?>
                                <div class="list-group-item bg-light">
                                    <div class="d-flex justify-content-between">
                                        <strong><?= htmlspecialchars($chat['sender'] ?? 'Assistant', ENT_QUOTES, 'UTF-8') ?></strong>
                                        <span class="text-muted small"><?= htmlspecialchars((string) $chat['created_at'], ENT_QUOTES, 'UTF-8') ?></span>
                                    </div>
                                    <p class="mb-0 text-muted small"><?= htmlspecialchars(substr((string) $chat['message'], 0, 120), ENT_QUOTES, 'UTF-8') ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="panel-card p-4">
                <div class="panel-header d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5>Clinical timeline</h5>
                        <p class="text-muted mb-0">Most recent measurements and alerts.</p>
                    </div>
                </div>
                <div class="timeline-list">
                    <?php foreach (array_slice($recentSymptomReports, 0, 5) as $report): ?>
                        <div class="timeline-item">
                            <strong><?= htmlspecialchars((string) $report['created_at'], ENT_QUOTES, 'UTF-8') ?></strong>
                            <p class="mb-0 text-muted small">Risk: <?= htmlspecialchars((string) $report['risk_level'], ENT_QUOTES, 'UTF-8') ?> • <?= htmlspecialchars(substr((string) $report['explanation'], 0, 90), ENT_QUOTES, 'UTF-8') ?>...</p>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($recentSymptomReports)): ?>
                        <div class="alert alert-secondary mb-0">No symptom assessments recorded yet.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-12">
            <div class="panel-card p-4">
                <div class="panel-header d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5>Report summary</h5>
                        <p class="text-muted mb-0">Your most recent lab reports and suggested follow-up actions.</p>
                    </div>
                    <a href="/lab-reports" class="btn btn-sm btn-outline-primary">View all lab reports</a>
                </div>
                <?php if (empty($recentLabReports)): ?>
                    <div class="alert alert-secondary">No lab reports uploaded yet.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>File</th>
                                    <th>Risk</th>
                                    <th>Summary</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($recentLabReports, 0, 5) as $report): ?>
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
                                        <td><?= htmlspecialchars(substr($report['report_summary'] ?? '', 0, 90), ENT_QUOTES, 'UTF-8') ?>...</td>
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
