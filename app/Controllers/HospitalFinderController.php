<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\CSRF;
use App\Core\Flash;
use App\Core\SessionManager;
use App\Middleware\AuthMiddleware;
use App\Models\DoctorModel;
use App\Models\HospitalModel;

final class HospitalFinderController extends BaseController
{
    public function index(): void
    {
        AuthMiddleware::requireAuth();

        $hospitalModel = new HospitalModel();
        $specialties = $hospitalModel->getAllSpecialties();

        echo $this->view('hospital-finder.index', [
            'pageTitle' => 'Hospital Finder',
            'user' => SessionManager::get('user', []),
            'specialties' => $specialties,
        ], 'dashboard');
    }

    public function search(): void
    {
        AuthMiddleware::requireAuth();

        if (! $this->isAjaxRequest()) {
            $this->redirect('/hospital-finder');
        }

        $latitude = isset($_GET['lat']) ? (float) $_GET['lat'] : 0.0;
        $longitude = isset($_GET['lng']) ? (float) $_GET['lng'] : 0.0;
        $search = trim((string) ($_GET['search'] ?? ''));
        $specialty = trim((string) ($_GET['specialty'] ?? ''));
        $rating = (float) ($_GET['rating'] ?? 0);
        $emergency = in_array($_GET['emergency'] ?? '', ['yes', 'no'], true) ? $_GET['emergency'] : '';
        $maxDistance = (int) ($_GET['distance'] ?? 25);

        $hospitalModel = new HospitalModel();
        $hospitals = $hospitalModel->searchHospitals([
            'search' => $search,
            'specialty' => $specialty,
            'rating' => $rating,
            'emergency' => $emergency,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'distance' => $maxDistance,
        ]);

        $recommendation = $this->recommendSpecialist($search, (string) ($_GET['symptoms'] ?? ''), (string) ($_GET['need'] ?? ''));
        $doctorModel = new DoctorModel();
        $recommendedDoctors = $doctorModel->findBySpecialty($recommendation['specialist']);

        $this->json([
            'success' => true,
            'hospitals' => $hospitals,
            'recommended_specialist' => $recommendation,
            'recommended_doctors' => $recommendedDoctors,
        ]);
    }

    public function nearbyServices(): void
    {
        AuthMiddleware::requireAuth();

        if (! $this->isAjaxRequest() || $_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->redirect('/hospital-finder');
        }

        $latitude = isset($_GET['lat']) ? (float) $_GET['lat'] : 0.0;
        $longitude = isset($_GET['lng']) ? (float) $_GET['lng'] : 0.0;
        $radius = 1800;

        $pharmacies = $this->fetchOverpass('pharmacy', $latitude, $longitude, $radius);
        $diagnosticCenters = $this->fetchOverpass('clinic', $latitude, $longitude, $radius, ['healthcare' => 'clinic']);

        $this->json([
            'success' => true,
            'pharmacies' => $pharmacies,
            'diagnostic_centers' => $diagnosticCenters,
        ]);
    }

    private function recommendSpecialist(string $search, string $symptoms, string $need): array
    {
        $text = strtolower(implode(' ', [$search, $symptoms, $need]));

        $mapping = [
            'heart|chest|palpitations|pressure|bp|blood pressure|cardiac' => 'Cardiologist',
            'skin|rash|acne|eczema|psoriasis|dermatology' => 'Dermatologist',
            'headache|migraine|vision|eye|nose|ear|throat' => 'General Physician',
            'pregnancy|obstetric|gynecology|gynecologist|female health' => 'Obstetrician / Gynecologist',
            'joint|bone|fracture|arthritis|back pain|orthopedic' => 'Orthopedic Specialist',
            'mental|anxiety|depression|psychiatry|psychiatrist|behavior' => 'Psychiatrist',
            'child|pediatric|infant|toddler|kids' => 'Pediatrician',
            'diabetes|blood sugar|hba1c|glucose' => 'Endocrinologist',
            'kidney|renal|creatinine|bun|urine' => 'Nephrologist',
            'liver|hepatitis|ast|alt|bilirubin' => 'Gastroenterologist',
            'allergy|asthma|breath|respiratory' => 'Pulmonologist',
            'teeth|dental|tooth|gum' => 'Dentist',
            'cancer|oncology|tumor|chemotherapy' => 'Oncologist',
            'emergency|urgent|severe|critical|accident' => 'Emergency Physician',
            'eye|vision|glaucoma|cataract|retina' => 'Ophthalmologist',
        ];

        foreach ($mapping as $pattern => $specialty) {
            if (preg_match('/' . $pattern . '/i', $text)) {
                return [
                    'specialist' => $specialty,
                    'reason' => 'Based on your symptoms and needs, a ' . $specialty . ' is likely the most appropriate specialist to consult.',
                ];
            }
        }

        return [
            'specialist' => 'General Physician',
            'reason' => 'Your symptoms are best addressed initially by a General Physician who can coordinate further specialist care.',
        ];
    }

    private function fetchOverpass(string $feature, float $latitude, float $longitude, int $radius, array $filters = []): array
    {
        $tag = $feature === 'pharmacy' ? 'amenity=pharmacy' : 'healthcare=clinic';
        $base = 'https://overpass-api.de/api/interpreter';
        $query = sprintf('[out:json][timeout:25];node(around:%d,%.6f,%.6f)[%s];out body;>;out skel qt;', $radius, $latitude, $longitude, $tag);

        $url = $base . '?data=' . rawurlencode($query);
        $payload = @file_get_contents($url);
        if ($payload === false) {
            return [];
        }

        $json = json_decode($payload, true);
        if (! is_array($json) || ! isset($json['elements'])) {
            return [];
        }

        $results = [];
        foreach ($json['elements'] as $element) {
            if (! isset($element['tags'], $element['lat'], $element['lon'])) {
                continue;
            }

            $results[] = [
                'name' => $element['tags']['name'] ?? ucfirst($feature),
                'address' => trim(($element['tags']['addr:street'] ?? '') . ' ' . ($element['tags']['addr:housenumber'] ?? '')),
                'phone' => $element['tags']['phone'] ?? $element['tags']['contact:phone'] ?? '',
                'website' => $element['tags']['website'] ?? '',
                'latitude' => $element['lat'],
                'longitude' => $element['lon'],
            ];
        }

        return $results;
    }

    private function isAjaxRequest(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
