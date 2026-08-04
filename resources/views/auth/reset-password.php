<div class="auth-card">
    <div class="card-header text-center mb-4">
        <h3>Create a new password</h3>
        <p class="mb-0">Your new password must be secure.</p>
    </div>

    <form method="post" action="/reset-password" class="needs-validation" novalidate>
        <?= csrf_field() ?>
        <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <div class="mb-3">
            <label for="password" class="form-label">New password</label>
            <input type="password" id="password" name="password" class="form-control" minlength="8" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm new password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" minlength="8" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Update password</button>
    </form>
</div>
