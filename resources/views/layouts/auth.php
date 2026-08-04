<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Login', ENT_QUOTES, 'UTF-8') ?> | MediAI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body class="auth-body">
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

    <div class="auth-shell">
        <div class="auth-panel">
            <div class="auth-brand">
                <div class="brand-icon"><i class="bi bi-heart-pulse"></i></div>
                <h2>MediAI</h2>
                <p>AI-powered healthcare assistant</p>
            </div>
            <div class="auth-content">
                <?= $content ?? '' ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/app.js"></script>
</body>
</html>
