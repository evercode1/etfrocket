<?php

namespace Tests\Unit\Imports;

use App\Imports\EtfDividendHistoryImport;
use InvalidArgumentException;
use RuntimeException;
use Tests\TestCase;

class EtfDividendHistoryImportUnitTest extends TestCase
{
    public function test_it_can_parse_etf_dividend_history_csv(): void
    {
        $filePath = $this->makeCsvFile([
            ['dividend_amount', 'declared_date', 'ex_dividend_date', 'record_date', 'payment_date', 'return_of_capital_percentage'],
            ['$0.2314', '2025-04-03', '2025-04-04', '2025-04-04', '2025-04-07', '0.0000'],
            ['$0.1987', '2025-04-10', '2025-04-11', '2025-04-11', '2025-04-14', '0.0000'],
        ]);

        $rows = (new EtfDividendHistoryImport())->parse($filePath);

        $this->assertCount(2, $rows);

        $this->assertSame([
            'dividend_amount' => '0.2314',
            'ex_dividend_date' => '2025-04-04',
            'payment_date' => '2025-04-07',
        ], $rows[0]);

        $this->assertSame([
            'dividend_amount' => '0.1987',
            'ex_dividend_date' => '2025-04-11',
            'payment_date' => '2025-04-14',
        ], $rows[1]);

        unlink($filePath);
    }

    public function test_it_normalizes_headers_using_snake_case(): void
    {
        $filePath = $this->makeCsvFile([
            ['Dividend Amount', 'Declared Date', 'Ex Dividend Date', 'Record Date', 'Payment Date', 'Return Of Capital Percentage'],
            ['$0.2314', '2025-04-03', '2025-04-04', '2025-04-04', '2025-04-07', '0.0000'],
        ]);

        $rows = (new EtfDividendHistoryImport())->parse($filePath);

        $this->assertSame('0.2314', $rows[0]['dividend_amount']);
        $this->assertSame('2025-04-04', $rows[0]['ex_dividend_date']);
        $this->assertSame('2025-04-07', $rows[0]['payment_date']);

        unlink($filePath);
    }

    public function test_it_strips_currency_symbols_and_commas_from_decimal_values(): void
    {
        $filePath = $this->makeCsvFile([
            ['dividend_amount', 'declared_date', 'ex_dividend_date', 'record_date', 'payment_date', 'return_of_capital_percentage'],
            ['$1,234.5678', '2025-04-03', '2025-04-04', '2025-04-04', '2025-04-07', '12.3456%'],
        ]);

        $rows = (new EtfDividendHistoryImport())->parse($filePath);

        $this->assertSame('1234.5678', $rows[0]['dividend_amount']);

        unlink($filePath);
    }

    public function test_it_skips_empty_rows(): void
    {
        $filePath = $this->makeCsvFile([
            ['dividend_amount', 'declared_date', 'ex_dividend_date', 'record_date', 'payment_date', 'return_of_capital_percentage'],
            ['$0.2314', '2025-04-03', '2025-04-04', '2025-04-04', '2025-04-07', '0.0000'],
            ['', '', '', '', '', ''],
            ['$0.1987', '2025-04-10', '2025-04-11', '2025-04-11', '2025-04-14', '0.0000'],
        ]);

        $rows = (new EtfDividendHistoryImport())->parse($filePath);

        $this->assertCount(2, $rows);

        unlink($filePath);
    }

    public function test_it_throws_exception_when_file_does_not_exist(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Import file not found at path [/bad/path.csv].');

        (new EtfDividendHistoryImport())->parse('/bad/path.csv');
    }

    public function test_it_throws_exception_when_required_column_is_missing(): void
    {
        $filePath = $this->makeCsvFile([
            ['declared_date', 'ex_dividend_date', 'record_date', 'payment_date', 'return_of_capital_percentage'],
            ['2025-04-03', '2025-04-04', '2025-04-04', '2025-04-07', '0.0000'],
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required column [dividend_amount].');

        try {
            (new EtfDividendHistoryImport())->parse($filePath);
        } finally {
            unlink($filePath);
        }
    }

    public function test_it_throws_exception_when_file_is_empty(): void
    {
        $filePath = tempnam(sys_get_temp_dir(), 'empty-etf-dividend-history-import-');

        file_put_contents($filePath, '');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Import file is empty.');

        try {
            (new EtfDividendHistoryImport())->parse($filePath);
        } finally {
            unlink($filePath);
        }
    }

    private function makeCsvFile(array $rows): string
    {
        $content = collect($rows)
            ->map(function (array $row) {
                return collect($row)
                    ->map(function ($value) {
                        $value = (string) $value;

                        return str_contains($value, ',')
                            ? "\"{$value}\""
                            : $value;
                    })
                    ->implode(',');
            })
            ->implode("\n");

        $filePath = tempnam(sys_get_temp_dir(), 'etf-dividend-history-import-');

        file_put_contents($filePath, $content);

        return $filePath;
    }
}