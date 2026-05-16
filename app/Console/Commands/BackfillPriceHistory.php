<?php

namespace App\Console\Commands;

use App\Models\Etf;
use App\Services\Imports\ImportEtfPriceHistoryService;
use Illuminate\Console\Command;

class BackfillPriceHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'etfs:backfill-price-history {symbol}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill ETF price history from a CSV import file';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $symbol = strtoupper(
            trim($this->argument('symbol'))
        );

        $etf = Etf::where('symbol', $symbol)
            ->first();

        $countBefore = \App\Models\EtfPriceHistory::where('etf_id', $etf->id)->count();

        $this->info("Rows before import: {$countBefore}");

        if (! $etf) {

            $this->error("ETF with symbol [{$symbol}] was not found.");

            return self::FAILURE;
        }

        $filePath = app_path("Imports/PriceData/" . strtolower($symbol) . ".csv");

        if (! file_exists($filePath)) {

            $this->error("Import file not found at [{$filePath}].");

            return self::FAILURE;
        }

        try {

            $results = (new ImportEtfPriceHistoryService())->import(
                $etf->id,
                $filePath
            );
        } catch (\Exception $e) {

            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info("Successfully imported price history for {$results['symbol']}.");

        $this->table([
            'ETF ID',
            'Symbol',
            'Rows Imported',
            'Rows Deleted', 
            'Start Date',
            'End Date',
        ], [[
            $results['etf_id'],
            $results['symbol'],
            $results['rows_imported'],
            $results['rows_deleted'],
            $results['start_date'],
            $results['end_date'],
            
        ]]);

        return self::SUCCESS;
    }
}
