<?php

declare(strict_types=1);

namespace App\Core;

final class LabReportAnalyzer
{
    private const PATTERNS = [
        'glucose' => ['label' => 'Glucose', 'normal' => [70, 99], 'unit' => 'mg/dL'],
        'hemoglobin' => ['label' => 'Hemoglobin', 'normal' => [13.5, 17.5], 'unit' => 'g/dL'],
        'hba1c' => ['label' => 'HbA1c', 'normal' => [4.0, 5.6], 'unit' => '%'],
        'cholesterol' => ['label' => 'Total Cholesterol', 'normal' => [0, 199], 'unit' => 'mg/dL'],
        'hdl' => ['label' => 'HDL Cholesterol', 'normal' => [40, 60], 'unit' => 'mg/dL'],
        'ldl' => ['label' => 'LDL Cholesterol', 'normal' => [0, 99], 'unit' => 'mg/dL'],
        'triglyceride' => ['label' => 'Triglycerides', 'normal' => [0, 149], 'unit' => 'mg/dL'],
        'wbc' => ['label' => 'White Blood Cells', 'normal' => [4.0, 11.0], 'unit' => 'x10^3/µL'],
        'rbc' => ['label' => 'Red Blood Cells', 'normal' => [4.5, 5.9], 'unit' => 'x10^6/µL'],
        'platelet' => ['label' => 'Platelets', 'normal' => [150, 450], 'unit' => 'x10^3/µL'],
        'sodium' => ['label' => 'Sodium', 'normal' => [135, 145], 'unit' => 'mmol/L'],
        'potassium' => ['label' => 'Potassium', 'normal' => [3.5, 5.1], 'unit' => 'mmol/L'],
        'creatinine' => ['label' => 'Creatinine', 'normal' => [0.6, 1.3], 'unit' => 'mg/dL'],
        'bun' => ['label' => 'BUN', 'normal' => [7, 20], 'unit' => 'mg/dL'],
        'ast' => ['label' => 'AST', 'normal' => [10, 40], 'unit' => 'U/L'],
        'alt' => ['label' => 'ALT', 'normal' => [7, 56], 'unit' => 'U/L'],
        'bilirubin' => ['label' => 'Bilirubin', 'normal' => [0.1, 1.2], 'unit' => 'mg/dL'],
        'calcium' => ['label' => 'Calcium', 'normal' => [8.6, 10.2], 'unit' => 'mg/dL'],
    ];

    public function analyze(string $text): array
    {
        $values = $this->extractValues($text);
        $analysis = $this->evaluateValues($values);

        return [
            'raw_text' => $text,
            'values' => $values,
            'abnormal' => array_values(array_filter($values, static fn (array $row): bool => $row['status'] !== 'normal')),
            'summary' => $analysis['summary'],
            'explanation' => $analysis['explanation'],
            'recommendations' => $analysis['recommendations'],
            'risk_level' => $analysis['risk_level'],
        ];
    }

    public function extractValues(string $text): array
    {
        $text = preg_replace('/\s+/', ' ', strtolower($text));
        $lines = preg_split('/[\r\n]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $values = [];

        foreach ($lines as $line) {
            foreach (self::PATTERNS as $key => $pattern) {
                if (str_contains($line, $key)) {
                    $match = [];
                    if (preg_match('/(\d+[.,]?\d*)\s*(mg\/?dl|mmol\/?l|g\/?dl|%|x10\^3\/µl|x10\^6\/µl|ul|u\/l)?/i', $line, $match)) {
                        $rawValue = str_replace(',', '.', $match[1]);
                        $value = (float) $rawValue;
                        $values[$pattern['label']] = [
                            'analyte' => $pattern['label'],
                            'value' => $value,
                            'unit' => $match[2] ?? $pattern['unit'],
                            'normal_range' => sprintf('%s - %s %s', $pattern['normal'][0], $pattern['normal'][1], $pattern['unit']),
                            'status' => 'normal',
                            'note' => '',
                        ];
                    }
                }
            }
        }

        return array_values($values);
    }

    private function evaluateValues(array $values): array
    {
        $abnormal = [];
        $recommendations = [];
        $risk = 'low';

        foreach ($values as $index => $value) {
            $pattern = $this->findPatternByLabel($value['analyte']);
            if ($pattern === null) {
                continue;
            }

            $status = 'normal';
            $note = '';
            $low = $pattern['normal'][0];
            $high = $pattern['normal'][1];

            if ($value['value'] < $low) {
                $status = 'low';
                $note = sprintf('%s is below the expected range.', $value['analyte']);
            }

            if ($value['value'] > $high) {
                $status = 'high';
                $note = sprintf('%s is above the expected range.', $value['analyte']);
            }

            if ($status !== 'normal') {
                $abnormal[] = $value;
                $recommendations[] = $this->recommendationForAnalyte($key = $this->normalizeKey($value['analyte']));
                if ($risk !== 'high') {
                    $risk = $status === 'high' ? 'high' : 'medium';
                }
            }

            $values[$index]['status'] = $status;
            $values[$index]['note'] = $note;
        }

        $summary = $this->buildSummary($abnormal);
        $explanation = $this->buildExplanation($values, $risk);
        $recommendations = array_values(array_filter(array_unique($recommendations)));

        return [
            'summary' => $summary,
            'explanation' => $explanation,
            'recommendations' => $recommendations,
            'risk_level' => $risk,
        ];
    }

    private function buildSummary(array $abnormal): string
    {
        if ($abnormal === []) {
            return 'No abnormal lab values were detected from the extracted report. Continue routine monitoring and discuss overall health with your provider.';
        }

        $phrases = array_map(static fn (array $item): string => sprintf('%s is %s the normal range', $item['analyte'], $item['status']), $abnormal);
        return 'Abnormal values detected: ' . implode(', ', $phrases) . '.';
    }

    private function buildExplanation(array $values, string $risk): string
    {
        $important = array_filter($values, static fn (array $item): bool => $item['status'] !== 'normal');
        if ($important === []) {
            return 'All extracted lab values fall within normal limits. Maintain your current care plan and review results with a healthcare professional as needed.';
        }

        $parts = ['The lab report contains several readings with deviations from normal ranges.'];

        foreach ($important as $value) {
            $parts[] = sprintf('%s: %s %s (%s).', $value['analyte'], $value['value'], $value['unit'], $value['note']);
        }

        $parts[] = sprintf('The overall risk assessment is %s. Focus on the highlighted values and discuss the suggested follow-up tests.', $risk);
        return implode(' ', $parts);
    }

    private function recommendationForAnalyte(string $key): string
    {
        return match ($key) {
            'glucose', 'hba1c' => 'Consider discussing diabetes evaluation and hemoglobin A1c follow-up testing.',
            'cholesterol', 'hdl', 'ldl', 'triglyceride' => 'Discuss cardiovascular risk and lipid panel follow-up with your provider.',
            'ast', 'alt', 'bilirubin' => 'Consider liver function follow-up and imaging or specialist review.',
            'creatinine', 'bun' => 'Kidney function follow-up tests may be appropriate.',
            'sodium', 'potassium' => 'Electrolyte balance should be reviewed with a medical professional.',
            default => 'Review these findings with your healthcare provider for further diagnostic guidance.',
        };
    }

    private function findPatternByLabel(string $label): ?array
    {
        foreach (self::PATTERNS as $pattern) {
            if (strcasecmp($pattern['label'], $label) === 0) {
                return $pattern;
            }
        }

        return null;
    }

    private function normalizeKey(string $label): string
    {
        return strtolower(str_replace([' ', '(', ')', '^', '/'], ['', '', '', '', ''], $label));
    }
}
