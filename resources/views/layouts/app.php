<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'MediAI', ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="description" content="AI-powered healthcare assistant platform.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="app-shell">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center gap-2" href="/">
                <span class="brand-icon"><i class="bi bi-heart-pulse"></i></span>
                <span>MediAI</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/health">Clinical Insights</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ai-assistant">AI Assistant</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-outline-light btn-sm" id="themeToggle" type="button" aria-label="Toggle dark mode">
                        <i class="bi bi-moon-stars"></i>
                    </button>
                    <a href="/login" class="btn btn-light btn-sm">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        <?= $content ?? '' ?>
    </main>

    <footer class="footer mt-auto">
        <div class="container py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span>© 2026 MediAI. Secure healthcare intelligence.</span>
            <span><i class="bi bi-shield-check"></i> HIPAA-ready foundation</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/app.js"></script>
</body>
</html>
