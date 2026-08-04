<div class="auth-card">
    <div class="card-header text-center mb-4">
        <h3>Create your account</h3>
        <p class="mb-0">Join MediAI for secure healthcare management.</p>
    </div>

    <form method="post" action="/register" class="needs-validation" novalidate>
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="name" class="form-label">Full name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($_SESSION['form_data']['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" id="role" class="form-select">
                <option value="patient" <?= (($_SESSION['form_data']['role'] ?? 'patient') === 'patient') ? 'selected' : '' ?>>Patient</option>
                <option value="admin" <?= (($_SESSION['form_data']['role'] ?? 'patient') === 'admin') ? 'selected' : '' ?>>Admin</option>
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
