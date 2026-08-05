<div class="panel-card">
    <div class="panel-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4>Hospital management</h4>
            <p class="text-muted mb-0">Manage hospital records, departments, and emergency availability.</p>
        </div>
        <div>
            <a href="/admin/hospitals/create" class="btn btn-primary">Add hospital</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Rating</th>
                    <th>Emergency</th>
                    <th>Phone</th>
                    <th>Departments</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hospitals as $hospital): ?>
                    <tr>
                        <td><?= htmlspecialchars($hospital['name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($hospital['address'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) $hospital['rating'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= $hospital['emergency_available'] === '1' ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' ?></td>
                        <td><?= htmlspecialchars($hospital['phone'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($hospital['departments'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <a href="/admin/hospitals/edit?id=<?= htmlspecialchars((string) $hospital['id'], ENT_QUOTES, 'UTF-8') ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="post" action="/admin/hospitals/delete" class="d-inline-block" style="margin:0;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= htmlspecialchars((string) $hospital['id'], ENT_QUOTES, 'UTF-8') ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this hospital?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
