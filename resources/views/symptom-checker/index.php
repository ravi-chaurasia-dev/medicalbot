<div class="panel-card">
    <div class="panel-header mb-4">
        <h4>Symptom Checker</h4>
        <p class="text-muted">Answer a few questions so MediAI can suggest possible conditions and next steps.</p>
    </div>

    <?php $formData = $_SESSION['form_data'] ?? []; unset($_SESSION['form_data']); ?>

    <form method="post" action="/symptom-checker/submit" class="symptom-form needs-validation" novalidate>
        <?= csrf_field() ?>

        <div class="row g-4">
            <div class="col-md-4">
                <label class="form-label">Age</label>
                <input type="number" name="age" class="form-control" min="1" max="120" value="<?= htmlspecialchars($formData['age'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select" required>
                    <option value="">Select</option>
                    <option value="male" <?= (($formData['gender'] ?? '') === 'male') ? 'selected' : '' ?>>Male</option>
                    <option value="female" <?= (($formData['gender'] ?? '') === 'female') ? 'selected' : '' ?>>Female</option>
                    <option value="other" <?= (($formData['gender'] ?? '') === 'other') ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Duration</label>
                <input type="text" name="duration" class="form-control" placeholder="e.g. 2 days" value="<?= htmlspecialchars($formData['duration'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Symptoms</label>
                <select name="symptoms[]" class="form-select" multiple required>
                    <?php $symptomOptions = ['Fever', 'Cough', 'Shortness of breath', 'Chest pain', 'Headache', 'Nausea', 'Dizziness', 'Abdominal pain', 'Fatigue', 'Sore throat']; ?>
                    <?php foreach ($symptomOptions as $option): ?>
                        <option value="<?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>" <?= in_array($option, $formData['symptoms'] ?? [], true) ? 'selected' : '' ?>><?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="form-text text-muted">Hold Ctrl or Cmd to select multiple symptoms.</small>
            </div>
            <div class="col-md-6">
                <label class="form-label">Pain level</label>
                <input type="range" name="pain_level" min="0" max="10" class="form-range" value="<?= htmlspecialchars($formData['pain_level'] ?? 0, ENT_QUOTES, 'UTF-8') ?>">
                <div class="d-flex justify-content-between mt-1"><span>0</span><span>10</span></div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Temperature (°C)</label>
                <input type="number" step="0.1" name="temperature" class="form-control" value="<?= htmlspecialchars($formData['temperature'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Current medicines</label>
                <input type="text" name="current_medicines" class="form-control" value="<?= htmlspecialchars($formData['current_medicines'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Smoking</label>
                <select name="smoking" class="form-select" required>
                    <option value="no" <?= (($formData['smoking'] ?? 'no') === 'no') ? 'selected' : '' ?>>No</option>
                    <option value="yes" <?= (($formData['smoking'] ?? '') === 'yes') ? 'selected' : '' ?>>Yes</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Alcohol</label>
                <select name="alcohol" class="form-select" required>
                    <option value="no" <?= (($formData['alcohol'] ?? 'no') === 'no') ? 'selected' : '' ?>>No</option>
                    <option value="yes" <?= (($formData['alcohol'] ?? '') === 'yes') ? 'selected' : '' ?>>Yes</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Exercise</label>
                <select name="exercise" class="form-select" required>
                    <option value="none" <?= (($formData['exercise'] ?? 'none') === 'none') ? 'selected' : '' ?>>None</option>
                    <option value="light" <?= (($formData['exercise'] ?? '') === 'light') ? 'selected' : '' ?>>Light</option>
                    <option value="moderate" <?= (($formData['exercise'] ?? '') === 'moderate') ? 'selected' : '' ?>>Moderate</option>
                    <option value="heavy" <?= (($formData['exercise'] ?? '') === 'heavy') ? 'selected' : '' ?>>Heavy</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Medical history</label>
                <input type="text" name="medical_history" class="form-control" value="<?= htmlspecialchars($formData['medical_history'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Family history</label>
                <textarea name="family_history" class="form-control" rows="3"><?= htmlspecialchars($formData['family_history'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
        </div>

        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-primary">Analyze symptoms</button>
        </div>
    </form>
</div>
