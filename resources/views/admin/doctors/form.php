<div class="panel-card">
    <div class="panel-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4><?= $doctor === null ? 'Add Doctor' : 'Edit Doctor' ?></h4>
            <p class="text-muted mb-0">Add or update a doctor profile for specialist recommendations.</p>
        </div>
    </div>

    <form method="post" action="/admin/doctors/save" class="row g-3">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars((string) ($doctor['id'] ?? 0), ENT_QUOTES, 'UTF-8') ?>">

        <div class="col-md-6">
            <label class="form-label">First name</label>
            <input type="text" name="first_name" value="<?= htmlspecialchars($doctor['first_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Last name</label>
            <input type="text" name="last_name" value="<?= htmlspecialchars($doctor['last_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Specialty</label>
            <input type="text" name="specialty" value="<?= htmlspecialchars($doctor['specialty'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Hospital</label>
            <select name="hospital_id" class="form-select" required>
                <option value="">Select hospital</option>
                <?php foreach ($hospitals as $hospitalItem): ?>
                    <option value="<?= htmlspecialchars((string) $hospitalItem['id'], ENT_QUOTES, 'UTF-8') ?>" <?= ($doctor['hospital_id'] ?? '') == $hospitalItem['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($hospitalItem['name'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($doctor['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="form-control">
        </div>

        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($doctor['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="form-control">
        </div>

        <div class="col-12">
            <label class="form-label">Availability</label>
            <input type="text" name="availability" value="<?= htmlspecialchars($doctor['availability'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="form-control" placeholder="Mon-Fri 9am-5pm">
        </div>

        <div class="col-12">
            <label class="form-label">Profile / description</label>
            <textarea name="profile" class="form-control" rows="4"><?= htmlspecialchars($doctor['profile'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Save Doctor</button>
            <a href="/admin/doctors" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
