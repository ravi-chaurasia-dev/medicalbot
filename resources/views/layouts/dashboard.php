<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="brand-box">
            <i class="bi bi-heart-pulse"></i>
            <span>MediAI</span>
        </div>
        <nav class="sidebar-nav">
            <a class="active" href="<?= url('/dashboard'); ?>"><i class="bi bi-grid-1x2-fill"></i>Dashboard</a>
            <a href="#"><i class="bi bi-person-lines-fill"></i>Patients</a>
            <a href="#"><i class="bi bi-calendar3"></i>Appointments</a>
            <a href="#"><i class="bi bi-capsule"></i>Medication</a>
            <a href="#"><i class="bi bi-graph-up-arrow"></i>Analytics</a>
            <a href="#"><i class="bi bi-gear"></i>Settings</a>
        </nav>
        <div class="sidebar-footer">
            <span><?= htmlspecialchars($userName ?? 'Clinician', ENT_QUOTES, 'UTF-8'); ?></span>
            <a href="<?= url('/logout'); ?>" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </aside>

    <div class="main-panel">
        <nav class="topbar">
            <div class="d-flex justify-content-between align-items-center w-100">
                <div>
                    <h4 class="mb-0">Clinical Overview</h4>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-outline-secondary btn-sm" id="themeToggle"><i class="bi bi-moon-stars"></i></button>
                    <div class="user-pill">Dr. <?= htmlspecialchars($userName ?? 'Clinician', ENT_QUOTES, 'UTF-8'); ?></div>
                </div>
            </div>
        </nav>

        <main class="content-area">
            <?= $content ?? ''; ?>
        </main>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
