<div class="panel-card">
    <div class="panel-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4><?= $hospital === null ? 'Add Hospital' : 'Edit Hospital' ?></h4>
            <p class="text-muted mb-0">Create or update a hospital entry for the finder network.</p>
        </div>
    </div>

    <form method="post" action="/admin/hospitals/save" class="row g-3">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars((string) ($hospital['id'] ?? 0), ENT_QUOTES, 'UTF-8') ?>">

        <div class="col-md-6">
            <label class="form-label">Hospital name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($hospital['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($hospital['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="form-control">
        </div>

        <div class="col-12">
            <label class="form-label">Address</label>
            <input type="text" name="address" value="<?= htmlspecialchars($hospital['address'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Latitude</label>
            <input type="text" name="latitude" value="<?= htmlspecialchars((string) ($hospital['latitude'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="form-control">
        </div>

        <div class="col-md-6">
            <label class="form-label">Longitude</label>
            <input type="text" name="longitude" value="<?= htmlspecialchars((string) ($hospital['longitude'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="form-control">
        </div>

        <div class="col-md-6">
            <label class="form-label">Website</label>
            <input type="text" name="website" value="<?= htmlspecialchars($hospital['website'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="form-control">
        </div>

        <div class="col-md-6">
            <label class="form-label">Rating</label>
            <input type="number" step="0.1" min="0" max="5" name="rating" value="<?= htmlspecialchars((string) ($hospital['rating'] ?? '0.0'), ENT_QUOTES, 'UTF-8') ?>" class="form-control">
        </div>

        <div class="col-12">
            <label class="form-label">Departments</label>
            <input type="text" name="departments" value="<?= htmlspecialchars($hospital['departments'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="form-control" placeholder="Cardiology, Emergency, Radiology">
        </div>

        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="emergency_available" id="emergencyAvailable" value="1" <?= ($hospital['emergency_available'] ?? '0') === '1' ? 'checked' : '' ?>>
                <label class="form-check-label" for="emergencyAvailable">Emergency availability</label>
            </div>
        </div>

        <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Save Hospital</button>
            <a href="/admin/hospitals" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
