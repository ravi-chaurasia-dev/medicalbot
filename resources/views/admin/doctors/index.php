<div class="panel-card">
    <div class="panel-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4>Doctor management</h4>
            <p class="text-muted mb-0">Manage specialist recommendations and doctor availability.</p>
        </div>
        <div>
            <a href="/admin/doctors/create" class="btn btn-primary">Add doctor</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Specialty</th>
                    <th>Hospital</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Availability</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($doctors as $doctor): ?>
                    <tr>
                        <td><?= htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($doctor['specialty'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($doctor['hospital_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($doctor['phone'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($doctor['email'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($doctor['availability'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <a href="/admin/doctors/edit?id=<?= htmlspecialchars((string) $doctor['id'], ENT_QUOTES, 'UTF-8') ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="post" action="/admin/doctors/delete" class="d-inline-block" style="margin:0;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= htmlspecialchars((string) $doctor['id'], ENT_QUOTES, 'UTF-8') ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this doctor?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
