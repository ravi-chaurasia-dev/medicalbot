<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Dashboard', ENT_QUOTES, 'UTF-8') ?> | MediAI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/assets/css/auth.css">
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
                <a class="nav-link" href="/ai-assistant"><i class="bi bi-robot"></i> AI Assistant</a>
                <a class="nav-link" href="/profile"><i class="bi bi-person-circle"></i> Profile</a>
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
    <script src="/assets/js/app.js"></script>
</body>
</html>
