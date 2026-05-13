<?php

namespace App\Console\Commands;

use App\Models\Etf;
use App\Models\PerformanceRangeType;
use App\Models\Status;
use App\Services\EtfMetrics\CalculateEtfMetricService;
use Illuminate\Console\Command;
use Throwable;

class CalculateEtfMetrics extends Command
{
    protected $signature = 'etf:calculate-metrics {--symbol= : Calculate metrics for a single ETF symbol}';

    protected $description = 'Calculate ETF performance metrics for all active ETFs and performance range types.';

    public function handle(CalculateEtfMetricService $service): int
    {
        $symbol = $this->option('symbol');

        $etfs = Etf::where('status_id', Status::ACTIVE)
            ->when($symbol, function ($query) use ($symbol) {
                $query->where('symbol', strtoupper($symbol));
            })
            ->orderBy('symbol')
            ->get();

        if ($etfs->isEmpty()) {
            $this->warn('No active ETFs found.');

            return self::SUCCESS;
        }

        $rangeTypes = PerformanceRangeType::orderBy('id')
            ->get();

        if ($rangeTypes->isEmpty()) {
            $this->warn('No active performance range types found.');

            return self::SUCCESS;
        }

        $this->info('Starting ETF metric calculations...');
        $this->info('ETFs found: ' . $etfs->count());
        $this->info('Range types found: ' . $rangeTypes->count());

        $successCount = 0;
        $failureCount = 0;

        foreach ($etfs as $etf) {

            $this->line('Processing ETF: ' . $etf->symbol);

            foreach ($rangeTypes as $rangeType) {

                try {

                    $service->calculate($etf, $rangeType->id);

                    $successCount++;

                    $this->line(
                        ' - Calculated ' . $rangeType->performance_range_type_name
                    );

                } catch (Throwable $e) {

                    $failureCount++;

                    report($e);

                    $this->error(
                        ' - Failed ' . $rangeType->performance_range_type_name . ': ' . $e->getMessage()
                    );
                }
            }
        }

        $this->info('ETF metric calculations complete.');
        $this->info('Successful calculations: ' . $successCount);
        $this->info('Failed calculations: ' . $failureCount);

        return $failureCount > 0 ? self::FAILURE : self::SUCCESS;
    }
}