<?php

namespace App\Imports;

use Carbon\Carbon;
use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;

class EtfDividendHistoryImport
{
    public function parse(string $filePath): array
    {
        if (! file_exists($filePath)) {
            throw new RuntimeException("Import file not found at path [{$filePath}].");
        }

        $handle = fopen($filePath, 'r');

        if (! $handle) {
            throw new RuntimeException("Unable to open import file at path [{$filePath}].");
        }

        $headers = fgetcsv($handle);

        if (! $headers) {
            fclose($handle);

            throw new RuntimeException('Import file is empty.');
        }

        $headers = collect($headers)
            ->map(fn($header) => Str::snake(strtolower(trim($header))))
            ->toArray();

        $rows = [];

        while (($data = fgetcsv($handle)) !== false) {

            if ($this->isEmptyRow($data)) {
                continue;
            }

            $row = array_combine($headers, $data);

            $rows[] = $this->normalizeRow($row);
        }

        fclose($handle);

        return $rows;
    }

    private function normalizeRow(array $row): array
    {
        foreach (
            [
                'dividend_amount',
                'ex_dividend_date',
                'payment_date',

            ] as $requiredColumn
        ) {

            if (! array_key_exists($requiredColumn, $row)) {
                throw new InvalidArgumentException("Missing required column [{$requiredColumn}].");
            }
        }

        return [
            'dividend_amount' => $this->normalizeDecimal($row['dividend_amount']),
            'ex_dividend_date' => $this->normalizeDate($row['ex_dividend_date']),
            'payment_date' => $this->normalizeDate($row['payment_date']),
        ];
    }

    private function normalizeDate(mixed $value): ?string
    {
        if (! $value) {
            return null;
        }

        return Carbon::parse($value)->format('Y-m-d');
    }

    private function normalizeDecimal(mixed $value): string
    {
        $value = str_replace(['$', '%', ','], '', (string) $value);

        return number_format((float) trim($value), 4, '.', '');
    }

    private function isEmptyRow(array $row): bool
    {
        return collect($row)
            ->filter(fn($value) => trim((string) $value) !== '')
            ->isEmpty();
    }
}
