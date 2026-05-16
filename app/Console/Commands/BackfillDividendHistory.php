<?php

namespace App\Console\Commands;

use App\Models\Etf;
use App\Services\Imports\ImportEtfDividendHistoryService;
use Illuminate\Console\Command;

class BackfillDividendHistory extends Command
{
    protected $signature = 'etfs:backfill-dividend-history {symbol}';

    protected $description = 'Backfill ETF dividend history from a CSV import file';

    public function handle(): int
    {
        $symbol = strtoupper(trim($this->argument('symbol')));

        $etf = Etf::where('symbol', $symbol)->first();

        if (! $etf) {
            $this->error("ETF with symbol [{$symbol}] was not found.");

            return self::FAILURE;
        }

        $filePath = app_path('Imports/DividendData/' . strtolower($symbol) . '_dividends.csv');

        if (! file_exists($filePath)) {
            $this->error("Import file not found at [{$filePath}].");

            return self::FAILURE;
        }

        try {

            $results = (new ImportEtfDividendHistoryService())->import(
                $etf->id,
                $filePath
            );
        } catch (\Exception $e) {

            $this->error(get_class($e));
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info("Successfully imported dividend history for {$results['symbol']}.");

        $this->table([
            'ETF ID',
            'Symbol',
            'Rows Deleted',
            'Rows Imported',
            'Start Date',
            'End Date',
        ], [[
            $results['etf_id'],
            $results['symbol'],
            $results['rows_deleted'],
            $results['rows_imported'],
            $results['start_date'],
            $results['end_date'],
        ]]);

        return self::SUCCESS;
    }
}
