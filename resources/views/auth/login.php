<div class="auth-card">
    <div class="card-header text-center mb-4">
        <h3>Welcome back</h3>
        <p class="mb-0">Sign in to your MediAI workspace.</p>
    </div>

    <?php $formData = $_SESSION['form_data'] ?? []; $formErrors = $_SESSION['form_errors'] ?? []; unset($_SESSION['form_data'], $_SESSION['form_errors']); ?>

    <?php if (! empty($formErrors)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars(implode(' ', $formErrors), ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <form method="post" action="/login" class="needs-validation ajax-form" data-ajax="true" novalidate>
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($formData['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me" value="1">
                <label class="form-check-label" for="remember_me">Remember me</label>
            </div>
            <a href="/forgot-password" class="small text-primary">Forgot password?</a>
        </div>

        <button type="submit" class="btn btn-primary w-100">Sign in</button>

        <div class="text-center mt-3">
            <small>Need an account? <a href="/register">Register now</a></small>
        </div>
    </form>
</div>
