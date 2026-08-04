<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="auth-layout">
    <div class="container-fluid h-100">
        <div class="row h-100 align-items-center">
            <div class="col-lg-7 d-none d-lg-flex auth-visual-panel">
                <div class="auth-visual-content">
                    <div class="badge bg-primary-subtle text-primary mb-3">Clinical Intelligence Platform</div>
                    <h1 class="display-5 fw-bold">AI-powered healthcare operations</h1>
                    <p class="lead mt-3">Monitor patient workflows, support clinical decisions, and streamline care coordination with secure automation.</p>
                    <div class="row g-3 mt-3">
                        <div class="col-6">
                            <div class="glass-card">
                                <i class="bi bi-clipboard-pulse"></i>
                                <strong>24/7 triage</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="glass-card">
                                <i class="bi bi-shield-lock"></i>
                                <strong>HIPAA aware</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 auth-form-panel">
                <?= $content ?? ''; ?>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
