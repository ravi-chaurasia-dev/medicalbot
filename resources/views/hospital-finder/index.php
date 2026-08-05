<div class="panel-card">
    <div class="panel-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4>Hospital Finder</h4>
            <p class="text-muted">Search nearby hospitals, compare specialists, and get directions using OpenStreetMap.</p>
        </div>
        <div>
            <button id="hospitalSearchButton" class="btn btn-primary">Find nearby hospitals</button>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-4">
            <div class="panel-card p-4 search-panel">
                <form id="hospitalFinderForm" class="row g-3">
                    <?= csrf_field() ?>
                    <div class="col-12">
                        <label class="form-label">Symptom keywords</label>
                        <textarea name="symptoms" class="form-control" rows="3" placeholder="e.g. chest pain, fever, headache"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Need or preference</label>
                        <input type="text" name="need" class="form-control" placeholder="e.g. urgent care, pediatric specialist, dermatology" />
                    </div>
                    <div class="col-12">
                        <label class="form-label">Hospital name or department</label>
                        <input type="text" name="search" class="form-control" placeholder="Cardiology, emergency, hospital name" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Specialty</label>
                        <select name="specialty" class="form-select">
                            <option value="">Any specialty</option>
                            <?php foreach ($specialties as $specialty): ?>
                                <option value="<?= htmlspecialchars($specialty, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($specialty, ENT_QUOTES, 'UTF-8') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Minimum rating</label>
                        <select name="rating" class="form-select">
                            <option value="0">Any</option>
                            <option value="3">3.0+</option>
                            <option value="4">4.0+</option>
                            <option value="4.5">4.5+</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Emergency available</label>
                        <select name="emergency" class="form-select">
                            <option value="">Any</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Search radius</label>
                        <select name="distance" class="form-select">
                            <option value="5">5 km</option>
                            <option value="10">10 km</option>
                            <option value="20">20 km</option>
                            <option value="50">50 km</option>
                        </select>
                    </div>
                    <div class="col-12 d-grid">
                        <button type="submit" class="btn btn-outline-primary">Search hospitals</button>
                    </div>
                </form>

                <div class="mt-4 specialist-recommendation d-none" id="specialistRecommendation">
                    <h6>Recommended specialist</h6>
                    <p class="mb-1"><strong id="specialistType"></strong></p>
                    <p id="specialistReason" class="text-muted"></p>
                </div>
            </div>

            <div class="panel-card p-4 nearby-panel d-none" id="nearbyServicesPanel">
                <h5>Nearby services</h5>
                <div>
                    <h6>Pharmacies</h6>
                    <ul class="list-group list-group-flush" id="pharmacyList"></ul>
                </div>
                <div class="mt-3">
                    <h6>Diagnostic centers</h6>
                    <ul class="list-group list-group-flush" id="diagnosticList"></ul>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="panel-card p-4 map-card mb-4">
                <div id="hospitalMap" class="hospital-map"></div>
            </div>

            <div class="panel-card p-4 result-panel">
                <h5>Nearby hospitals</h5>
                <div id="hospitalResults" class="hospital-results"></div>
            </div>
        </div>
    </div>
</div>
