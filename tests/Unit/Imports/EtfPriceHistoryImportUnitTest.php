<?php

namespace Tests\Unit\Imports;

use App\Imports\EtfPriceHistoryImport;
use InvalidArgumentException;
use RuntimeException;
use Tests\TestCase;

class EtfPriceHistoryImportUnitTest extends TestCase
{
    public function test_it_can_parse_etf_price_history_csv(): void
    {
        $filePath = $this->makeCsvFile([
            ['Date', 'Open', 'High', 'Low', 'Close', 'Volume'],
            ['2025-04-04', '44.0000', '44.00', '42.2194', '42.2194', '2312'],
            ['2025-04-07', '40.3953', '43.79', '40.3953', '43.1155', '1284'],
        ]);

        $rows = (new EtfPriceHistoryImport())->parse($filePath);

        $this->assertCount(2, $rows);

        $this->assertSame([
            'price_date' => '2025-04-04',
            'close_price' => '42.2194',
            'volume' => 2312,
        ], $rows[0]);

        $this->assertSame([
            'price_date' => '2025-04-07',
            'close_price' => '43.1155',
            'volume' => 1284,
        ], $rows[1]);

        unlink($filePath);
    }

    public function test_it_normalizes_headers_using_snake_case(): void
    {
        $filePath = $this->makeCsvFile([
            ['DATE', 'OPEN', 'HIGH', 'LOW', 'CLOSE', 'VOLUME'],
            ['2025-04-04', '44.0000', '44.00', '42.2194', '42.2194', '2312'],
        ]);

        $rows = (new EtfPriceHistoryImport())->parse($filePath);

        $this->assertSame('2025-04-04', $rows[0]['price_date']);
        $this->assertSame('42.2194', $rows[0]['close_price']);
        $this->assertSame(2312, $rows[0]['volume']);

        unlink($filePath);
    }

    public function test_it_skips_empty_rows(): void
    {
        $filePath = $this->makeCsvFile([
            ['Date', 'Open', 'High', 'Low', 'Close', 'Volume'],
            ['2025-04-04', '44.0000', '44.00', '42.2194', '42.2194', '2312'],
            ['', '', '', '', '', ''],
            ['2025-04-07', '40.3953', '43.79', '40.3953', '43.1155', '1284'],
        ]);

        $rows = (new EtfPriceHistoryImport())->parse($filePath);

        $this->assertCount(2, $rows);

        unlink($filePath);
    }

    public function test_it_throws_exception_when_file_does_not_exist(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Import file not found at path [/bad/path.csv].');

        (new EtfPriceHistoryImport())->parse('/bad/path.csv');
    }

    public function test_it_throws_exception_when_required_column_is_missing(): void
    {
        $filePath = $this->makeCsvFile([
            ['Date', 'Open', 'High', 'Low', 'Volume'],
            ['2025-04-04', '44.0000', '44.00', '42.2194', '2312'],
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required column [close].');

        try {
            (new EtfPriceHistoryImport())->parse($filePath);
        } finally {
            unlink($filePath);
        }
    }

    public function test_it_throws_exception_when_file_is_empty(): void
    {
        $filePath = tempnam(sys_get_temp_dir(), 'empty-etf-price-history-import-');

        file_put_contents($filePath, '');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Import file is empty.');

        try {
            (new EtfPriceHistoryImport())->parse($filePath);
        } finally {
            unlink($filePath);
        }
    }

    private function makeCsvFile(array $rows): string
    {
        $content = collect($rows)
            ->map(function (array $row) {
                return collect($row)
                    ->map(fn ($value) => str_contains((string) $value, ',') ? "\"{$value}\"" : $value)
                    ->implode(',');
            })
            ->implode("\n");

        $filePath = tempnam(sys_get_temp_dir(), 'etf-price-history-import-');

        file_put_contents($filePath, $content);

        return $filePath;
    }
}