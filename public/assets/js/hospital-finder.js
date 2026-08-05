document.addEventListener('DOMContentLoaded', () => {
    const mapElement = document.getElementById('hospitalMap');
    const form = document.getElementById('hospitalFinderForm');
    const resultsContainer = document.getElementById('hospitalResults');
    const specialistType = document.getElementById('specialistType');
    const specialistReason = document.getElementById('specialistReason');
    const recommendationPanel = document.getElementById('specialistRecommendation');
    const pharmacyList = document.getElementById('pharmacyList');
    const diagnosticList = document.getElementById('diagnosticList');
    const nearbyPanel = document.getElementById('nearbyServicesPanel');
    const searchButton = document.getElementById('hospitalSearchButton');

    if (! form || ! mapElement || ! resultsContainer) {
        return;
    }

    let map;
    let markers = [];

    const initMap = (lat, lng) => {
        if (!map) {
            map = L.map('hospitalMap').setView([lat, lng], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
            }).addTo(map);
        } else {
            map.setView([lat, lng], 12);
        }
    };

    const clearMarkers = () => {
        markers.forEach((marker) => marker.remove());
        markers = [];
    };

    const addMarker = (hospital, index) => {
        const marker = L.marker([hospital.latitude, hospital.longitude]).addTo(map);
        marker.bindPopup(`<strong>${hospital.name}</strong><br>${hospital.address}<br>${hospital.distance.toFixed(1)} km away`);
        markers.push(marker);
    };

    const renderHospitals = (hospitals) => {
        resultsContainer.innerHTML = '';
        clearMarkers();

        if (hospitals.length === 0) {
            resultsContainer.innerHTML = '<div class="alert alert-secondary">No hospitals found nearby.</div>';
            return;
        }

        hospitals.forEach((hospital, index) => {
            const card = document.createElement('div');
            card.className = 'hospital-result-card mb-3 p-3 border rounded';
            card.innerHTML = `
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h6 class="mb-1">${hospital.name}</h6>
                        <p class="mb-1 text-muted">${hospital.address}</p>
                    </div>
                    <span class="badge bg-primary">${hospital.distance.toFixed(1)} km</span>
                </div>
                <div class="row gy-2">
                    <div class="col-md-6"><strong>Rating:</strong> ${hospital.rating || 'N/A'}</div>
                    <div class="col-md-6"><strong>Emergency:</strong> ${hospital.emergency_available === '1' ? 'Yes' : 'No'}</div>
                    <div class="col-md-6"><strong>Phone:</strong> ${hospital.phone || 'N/A'}</div>
                    <div class="col-md-6"><strong>Website:</strong> ${hospital.website ? `<a href="${hospital.website}" target="_blank">Visit</a>` : 'N/A'}</div>
                    <div class="col-12"><strong>Departments:</strong> ${hospital.departments || 'N/A'}</div>
                </div>
                <div class="mt-3 d-flex gap-2 flex-wrap">
                    <button class="btn btn-sm btn-outline-secondary" data-lat="${hospital.latitude}" data-lng="${hospital.longitude}">Go to map</button>
                    <a href="https://www.openstreetmap.org/directions?from=&to=${hospital.latitude},${hospital.longitude}" class="btn btn-sm btn-outline-primary" target="_blank">Directions</a>
                </div>
            `;
            resultsContainer.appendChild(card);
            addMarker(hospital, index);

            card.querySelector('button')?.addEventListener('click', () => {
                map.setView([hospital.latitude, hospital.longitude], 15);
            });
        });
    };

    const renderNearbyServices = (pharmacies, diagnostics) => {
        pharmacyList.innerHTML = pharmacies.length === 0 ? '<li class="list-group-item">No nearby pharmacies found.</li>' : pharmacies.map((item) => `<li class="list-group-item"><strong>${item.name}</strong><br>${item.address}<br>${item.phone || ''}</li>`).join('');
        diagnosticList.innerHTML = diagnostics.length === 0 ? '<li class="list-group-item">No nearby diagnostic centers found.</li>' : diagnostics.map((item) => `<li class="list-group-item"><strong>${item.name}</strong><br>${item.address}<br>${item.phone || ''}</li>`).join('');
        nearbyPanel.classList.remove('d-none');
    };

    const loadNearbyServices = (lat, lng) => {
        fetch(`/hospital-finder/nearby-services?lat=${lat}&lng=${lng}`)
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    renderNearbyServices(data.pharmacies, data.diagnostic_centers);
                }
            });
    };

    const performSearch = (lat, lng) => {
        const formData = new FormData(form);
        const params = new URLSearchParams();
        params.append('lat', lat.toString());
        params.append('lng', lng.toString());

        formData.forEach((value, key) => {
            params.append(key, String(value));
        });

        fetch(`/hospital-finder/search?${params.toString()}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
            .then((response) => response.json())
            .then((data) => {
                if (! data.success) {
                    resultsContainer.innerHTML = '<div class="alert alert-danger">Unable to load hospitals.</div>';
                    return;
                }

                if (data.recommended_specialist) {
                    specialistType.textContent = data.recommended_specialist.specialist;
                    specialistReason.textContent = data.recommended_specialist.reason;
                    recommendationPanel.classList.remove('d-none');
                }

                renderHospitals(data.hospitals);
                loadNearbyServices(lat, lng);
            })
            .catch(() => {
                resultsContainer.innerHTML = '<div class="alert alert-danger">Unable to load hospitals.</div>';
            });
    };

    const requestLocation = () => {
        if (! navigator.geolocation) {
            alert('Geolocation is not supported by your browser. Please enter a location manually.');
            return;
        }

        navigator.geolocation.getCurrentPosition((position) => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            initMap(lat, lng);
            performSearch(lat, lng);
        }, () => {
            alert('Unable to fetch your location. Please try again or enter coordinates manually.');
            initMap(20.5937, 78.9629);
        });
    };

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        navigator.geolocation.getCurrentPosition((position) => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            performSearch(lat, lng);
        }, () => {
            alert('Unable to get your location. Search requires location permission.');
        });
    });

    searchButton.addEventListener('click', () => {
        requestLocation();
    });

    initMap(20.5937, 78.9629);
});
