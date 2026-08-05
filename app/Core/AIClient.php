<?php

declare(strict_types=1);

namespace App\Core;

final class AIClient
{
    public function analyzeSymptoms(array $payload): array
    {
        $apiKey = env('AI_API_KEY', '');
        $apiUrl = env('AI_API_URL', 'https://api.openai.com/v1/chat/completions');
        $model = env('AI_MODEL', 'gpt-4o-mini');

        if ($apiKey === '') {
            return ['success' => false, 'message' => 'Missing AI API key.'];
        }

        $prompt = $this->buildPrompt($payload);
        $body = json_encode([
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => 'You are a medical reasoning assistant. Answer as JSON only.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.2,
            'max_tokens' => 650,
        ], JSON_THROW_ON_ERROR);

        $headers = [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || $statusCode >= 400) {
            return ['success' => false, 'message' => 'AI request failed: ' . ($error ?: 'HTTP ' . $statusCode)];
        }

        $payload = json_decode($response, true);
        if (! is_array($payload) || ! isset($payload['choices'][0]['message']['content'])) {
            return ['success' => false, 'message' => 'Unable to parse AI response.'];
        }

        $content = trim((string) $payload['choices'][0]['message']['content']);
        $json = $this->extractJson($content);

        if ($json === null) {
            return ['success' => false, 'message' => 'AI response did not contain valid JSON.'];
        }

        return ['success' => true, 'data' => $json];
    }

    private function buildPrompt(array $payload): string
    {
        return sprintf(
            "Analyze the following patient symptom report and return a JSON object with keys: conditions, risk_level, emergency_warning, explanation, suggested_tests, confidence, follow_up_questions. Use these values strictly. Input: %s",
            json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );
    }

    private function extractJson(string $content): ?array
    {
        $start = strpos($content, '{');
        $end = strrpos($content, '}');

        if ($start === false || $end === false || $end < $start) {
            return null;
        }

        $jsonString = substr($content, $start, $end - $start + 1);

        try {
            $decoded = json_decode($jsonString, true, 512, JSON_THROW_ON_ERROR);
            return $decoded;
        } catch (\JsonException) {
            return null;
        }
    }
}
