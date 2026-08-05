<div class="auth-card">
    <div class="card-header text-center mb-4">
        <h3>Create your account</h3>
        <p class="mb-0">Join MediAI for secure healthcare management.</p>
    </div>

    <?php $formData = $_SESSION['form_data'] ?? []; $formErrors = $_SESSION['form_errors'] ?? []; unset($_SESSION['form_data'], $_SESSION['form_errors']); ?>

    <?php if (! empty($formErrors)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars(implode(' ', $formErrors), ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <form method="post" action="/register" class="needs-validation ajax-form" data-ajax="true" novalidate>
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="name" class="form-label">Full name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($formData['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($formData['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" id="role" class="form-select">
                <option value="patient" <?= (($formData['role'] ?? 'patient') === 'patient') ? 'selected' : '' ?>>Patient</option>
                <option value="admin" <?= (($formData['role'] ?? 'patient') === 'admin') ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" minlength="8" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" minlength="8" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Create account</button>

        <div class="text-center mt-3">
            <small>Already have an account? <a href="/login">Sign in</a></small>
        </div>
    </form>
</div>
