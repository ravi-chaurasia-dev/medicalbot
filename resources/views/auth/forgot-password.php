<div class="auth-card">
    <div class="card-header text-center mb-4">
        <h3>Reset your password</h3>
        <p class="mb-0">Enter your email to receive a reset link.</p>
    </div>

    <?php $formData = $_SESSION['form_data'] ?? []; unset($_SESSION['form_data']); ?>

    <form method="post" action="/forgot-password" class="needs-validation ajax-form" data-ajax="true" novalidate>
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($formData['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Send reset link</button>

        <div class="text-center mt-3">
            <a href="/login">Back to login</a>
        </div>
    </form>
</div>
