<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Dashboard', ENT_QUOTES, 'UTF-8') ?> | MediAI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-o9N1j7k2Qf2YS7H7OKeGvOl6b3VVLEw0xj2MYC/o5r0=" crossorigin="" />
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/assets/css/auth.css">
    <link rel="stylesheet" href="/assets/css/symptom-checker.css">
    <link rel="stylesheet" href="/assets/css/hospital-finder.css">
</head>
<body class="dashboard-body">
    <div class="loading-overlay" aria-live="polite">
        <div class="loader-ring"></div>
    </div>

    <?php $flashMessages = \App\Core\Flash::get(); ?>
    <?php if ($flashMessages !== []): ?>
        <div class="container mt-3">
            <?php foreach ($flashMessages as $flash): ?>
                <div class="alert alert-<?= htmlspecialchars((string) ($flash['type'] ?? 'info'), ENT_QUOTES, 'UTF-8') ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars((string) $flash['message'], ENT_QUOTES, 'UTF-8') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="app-layout">
        <aside class="sidebar">
            <div class="brand-box">
                <div class="brand-icon"><i class="bi bi-heart-pulse"></i></div>
                <div>
                    <h5>MediAI</h5>
                    <small>Clinical Console</small>
                </div>
            </div>

            <nav class="nav flex-column sidebar-nav">
                <a class="nav-link active" href="/dashboard"><i class="bi bi-grid-1x2-fill"></i> Overview</a>
                <a class="nav-link" href="/health"><i class="bi bi-clipboard-pulse"></i> Health Records</a>
                <a class="nav-link" href="/symptom-checker"><i class="bi bi-heart-pulse"></i> Symptom Checker</a>
                <a class="nav-link" href="/symptom-checker/history"><i class="bi bi-clock-history"></i> Symptom History</a>
                <a class="nav-link" href="/hospital-finder"><i class="bi bi-location-plus"></i> Hospital Finder</a>
                <a class="nav-link" href="/lab-reports"><i class="bi bi-file-earmark-medical"></i> Lab Reports</a>
                <a class="nav-link" href="/ai-assistant"><i class="bi bi-robot"></i> AI Assistant</a>
                <a class="nav-link" href="/profile"><i class="bi bi-person-circle"></i> Profile</a>
                <?php if (($user['role'] ?? '') === 'admin'): ?>
                    <a class="nav-link" href="/admin/dashboard"><i class="bi bi-speedometer2"></i> Admin Overview</a>
                    <a class="nav-link" href="/admin/reports"><i class="bi bi-file-earmark-text"></i> Report Management</a>
                    <a class="nav-link" href="/admin/users"><i class="bi bi-people-fill"></i> User Management</a>
                    <a class="nav-link" href="/admin/hospitals"><i class="bi bi-hospital"></i> Hospital Management</a>
                    <a class="nav-link" href="/admin/doctors"><i class="bi bi-person-badge"></i> Doctor Management</a>
                <?php endif; ?>
                <a class="nav-link" href="/logout"><i class="bi bi-box-arrow-left"></i> Logout</a>
            </nav>

            <div class="sidebar-footer">
                <span class="status-dot"></span>
                System online
            </div>
        </aside>

        <div class="main-panel">
            <header class="topbar">
                <div>
                    <h3 class="mb-0"><?= htmlspecialchars($pageTitle ?? 'Dashboard', ENT_QUOTES, 'UTF-8') ?></h3>
                </div>
                <div class="topbar-actions">
                    <button class="btn btn-outline-secondary btn-sm" id="themeToggle" type="button">
                        <i class="bi bi-moon-stars"></i>
                    </button>
                    <div class="user-badge">
                        <i class="bi bi-person-circle"></i>
                        <span><?= htmlspecialchars($user['name'] ?? 'Healthcare User', ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                </div>
            </header>

            <div class="content-area">
                <?= $content ?? '' ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-o9N1j7k2Qf2YS7H7OKeGvOl6b3VVLEw0xj2MYC/o5r0=" crossorigin=""></script>
    <script src="/assets/js/app.js"></script>
    <script src="/assets/js/symptom-checker.js"></script>
    <script src="/assets/js/hospital-finder.js"></script>
</body>
</html>
