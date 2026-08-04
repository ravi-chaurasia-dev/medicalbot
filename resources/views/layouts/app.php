<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="app-shell">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="<?= url('/'); ?>">
                <i class="bi bi-heart-pulse me-2"></i>MediAI
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="<?= url('/'); ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('/dashboard'); ?>">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('/login'); ?>">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        <?= $content ?? ''; ?>
    </main>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
