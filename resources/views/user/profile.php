<div class="profile-layout">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="panel-card text-center p-4">
                <div class="profile-photo mx-auto mb-3">
                    <?php $photo = $profile['photo_path'] ?? ''; ?>
                    <?php if ($photo !== ''): ?>
                        <img src="<?= htmlspecialchars($photo, ENT_QUOTES, 'UTF-8') ?>" alt="Profile photo" class="img-fluid rounded-circle">
                    <?php else: ?>
                        <i class="bi bi-person-circle"></i>
                    <?php endif; ?>
                </div>
                <h4><?= htmlspecialchars($user['name'] ?? 'User', ENT_QUOTES, 'UTF-8') ?></h4>
                <p class="text-muted mb-3"><?= htmlspecialchars($user['role'] ?? 'patient', ENT_QUOTES, 'UTF-8') ?></p>

                <form method="post" action="/profile/upload-photo" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <input type="file" name="photo" class="form-control" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Upload Photo</button>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="panel-card p-4">
                <h4 class="mb-4">Personal Information</h4>
                <form method="post" action="/profile/save">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Age</label>
                            <input type="number" name="age" class="form-control" value="<?= htmlspecialchars((string) ($profile['age'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Select</option>
                                <option value="male" <?= (($profile['gender'] ?? '') === 'male') ? 'selected' : '' ?>>Male</option>
                                <option value="female" <?= (($profile['gender'] ?? '') === 'female') ? 'selected' : '' ?>>Female</option>
                                <option value="other" <?= (($profile['gender'] ?? '') === 'other') ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Blood Group</label>
                            <input type="text" name="blood_group" class="form-control" value="<?= htmlspecialchars((string) ($profile['blood_group'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Height</label>
                            <input type="text" name="height" class="form-control" value="<?= htmlspecialchars((string) ($profile['height'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Weight</label>
                            <input type="text" name="weight" class="form-control" value="<?= htmlspecialchars((string) ($profile['weight'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Emergency Contact</label>
                            <input type="text" name="emergency_contact" class="form-control" value="<?= htmlspecialchars((string) ($profile['emergency_contact'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="3"><?= htmlspecialchars((string) ($profile['address'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h4 class="mb-3">Medical History</h4>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Diseases</label>
                            <textarea name="diseases" class="form-control" rows="2"><?= htmlspecialchars((string) ($history['diseases'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Surgeries</label>
                            <textarea name="surgeries" class="form-control" rows="2"><?= htmlspecialchars((string) ($history['surgeries'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Current Medications</label>
                            <textarea name="current_medications" class="form-control" rows="2"><?= htmlspecialchars((string) ($history['current_medications'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Allergies</label>
                            <textarea name="allergies" class="form-control" rows="2"><?= htmlspecialchars((string) ($history['allergies'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Vaccination History</label>
                            <textarea name="vaccination_history" class="form-control" rows="2"><?= htmlspecialchars((string) ($history['vaccination_history'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Family Medical History</label>
                            <textarea name="family_medical_history" class="form-control" rows="2"><?= htmlspecialchars((string) ($history['family_medical_history'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary">Save profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
