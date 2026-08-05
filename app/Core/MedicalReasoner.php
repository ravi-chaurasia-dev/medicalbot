<?php

declare(strict_types=1);

namespace App\Core;

final class MedicalReasoner
{
    public static function analyze(array $input): array
    {
        $aiClient = new AIClient();
        $aiResponse = $aiClient->analyzeSymptoms($input);

        if ($aiResponse['success'] === true && isset($aiResponse['data']) && is_array($aiResponse['data'])) {
            $data = $aiResponse['data'];
            return array_merge(self::normalizeData($data), ['source' => 'ai']);
        }

        return array_merge(self::localReasoning($input), ['source' => 'local']);
    }

    private static function normalizeData(array $data): array
    {
        return [
            'conditions' => array_values(array_filter((array) ($data['conditions'] ?? []), 'is_string')),
            'risk_level' => in_array($data['risk_level'] ?? '', ['low', 'medium', 'high'], true) ? $data['risk_level'] : 'medium',
            'emergency_warning' => (string) ($data['emergency_warning'] ?? ''),
            'explanation' => (string) ($data['explanation'] ?? ''),
            'suggested_tests' => (string) ($data['suggested_tests'] ?? ''),
            'confidence' => self::normalizeConfidence($data['confidence'] ?? null),
            'follow_up_questions' => array_values((array) ($data['follow_up_questions'] ?? [])),
        ];
    }

    private static function normalizeConfidence(mixed $value): float
    {
        if (is_numeric($value)) {
            $confidence = (float) $value;
        } elseif (is_string($value) && preg_match('/\d+(?:\.\d+)?/', $value, $matches)) {
            $confidence = (float) $matches[0];
        } else {
            $confidence = 75.0;
        }

        return min(100.0, max(0.0, $confidence));
    }

    private static function localReasoning(array $input): array
    {
        $symptoms = array_map('strtolower', $input['symptoms'] ?? []);
        $painLevel = (int) ($input['pain_level'] ?? 0);
        $temperature = (float) ($input['temperature'] ?? 0.0);
        $duration = strtolower((string) ($input['duration'] ?? ''));

        $conditions = [];
        $followUps = [];
        $emergencyWarning = '';
        $riskLevel = 'low';
        $suggestedTests = [];
        $confidence = 70.0;

        if (in_array('chest pain', $symptoms, true) || in_array('shortness of breath', $symptoms, true) || in_array('dizziness', $symptoms, true)) {
            $conditions[] = 'Cardiac stress or angina';
            $riskLevel = 'high';
            $emergencyWarning = 'Seek immediate medical attention if symptoms worsen, especially chest pain or difficulty breathing.';
            $suggestedTests[] = 'ECG';
            $suggestedTests[] = 'Chest X-ray';
            $confidence += 10;
        }

        if (in_array('fever', $symptoms, true) || $temperature >= 38.0) {
            $conditions[] = 'Infection or inflammation';
            if ($riskLevel !== 'high') {
                $riskLevel = 'medium';
            }
            $suggestedTests[] = 'Complete blood count';
            $confidence += 5;
        }

        if (in_array('headache', $symptoms, true)) {
            $conditions[] = 'Tension headache or migraine';
            if ($painLevel >= 7) {
                $riskLevel = 'medium';
                $suggestedTests[] = 'Neurological evaluation';
                $confidence += 5;
            }
        }

        if (in_array('abdominal pain', $symptoms, true)) {
            $conditions[] = 'Gastrointestinal irritation';
            $suggestedTests[] = 'Abdominal ultrasound';
            if ($duration === 'more than a week') {
                $riskLevel = max($riskLevel, 'medium');
                $confidence += 5;
            }
        }

        if (in_array('cough', $symptoms, true) || in_array('shortness of breath', $symptoms, true)) {
            $conditions[] = 'Respiratory infection or asthma flare';
            $suggestedTests[] = 'Pulmonary function test';
            if ($temperature >= 38.5) {
                $riskLevel = 'high';
                $confidence += 5;
            }
        }

        if (in_array('nausea', $symptoms, true) || in_array('vomiting', $symptoms, true)) {
            $conditions[] = 'Gastroenteritis or medication reaction';
            $suggestedTests[] = 'Electrolyte panel';
        }

        if ($painLevel >= 8) {
            $riskLevel = 'high';
            $suggestedTests[] = 'Urgent specialist evaluation';
            $confidence += 5;
        }

        if (in_array('chest pain', $symptoms, true)) {
            $followUps[] = 'Does the pain radiate to your jaw, arm, or back?';
            $followUps[] = 'Is it worse with activity or improved by rest?';
        }

        if (in_array('shortness of breath', $symptoms, true)) {
            $followUps[] = 'Do you feel short of breath at rest or only during activity?';
            $followUps[] = 'Do you notice any swelling in your legs or ankles?';
        }

        if (empty($followUps)) {
            $followUps[] = 'When did these symptoms begin?';
            $followUps[] = 'Have you experienced similar symptoms before?';
        }

        $conditions = array_values(array_unique($conditions));
        if ($conditions === []) {
            $conditions[] = 'Nonspecific symptoms likely related to common illness';
            $confidence -= 10;
            $suggestedTests[] = 'General physical exam';
        }

        $suggestedTests = array_values(array_unique($suggestedTests));

        $explanation = self::buildExplanation($input, $conditions, $riskLevel);
        $confidence = min(100.0, max(30.0, $confidence));

        return [
            'conditions' => $conditions,
            'risk_level' => $riskLevel,
            'emergency_warning' => $emergencyWarning,
            'explanation' => $explanation,
            'suggested_tests' => implode('; ', $suggestedTests),
            'confidence' => round($confidence, 1),
            'follow_up_questions' => $followUps,
        ];
    }

    private static function buildExplanation(array $input, array $conditions, string $riskLevel): string
    {
        $parts = [];
        $parts[] = sprintf('The patient is a %s-year-old %s presenting with %s.',
            (int) $input['age'],
            $input['gender'] ?? 'patient',
            implode(', ', $input['symptoms'] ?? [])
        );

        if ((float) ($input['temperature'] ?? 0) >= 38.0) {
            $parts[] = 'Fever supports possible infection or inflammation.';
        }

        $parts[] = sprintf('Based on symptom patterns, the most likely conditions include %s.', implode(', ', $conditions));
        $parts[] = sprintf('The evaluated risk level is %s and the confidence is %.1f%%.', $riskLevel, self::normalizeConfidence($input['confidence'] ?? 75));

        return implode(' ', $parts);
    }
}
