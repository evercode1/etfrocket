<?php

namespace App\Console\Commands;

use App\Models\Etf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Services\AI\Extractions\AiEtfDataExtractionService;
use App\Services\AI\Extractions\ProcessAiEtfDataExtractionService;

class RunAiEtfDataExtractions extends Command
{
    protected $signature = 'etfs:run-ai-extraction
        {--symbol= : Run extraction for a single ETF symbol}
        {--limit= : Limit the number of ETFs processed}';

    protected $description = 'Run AI ETF data extraction and process extracted ETF data.';

    public function handle(
        AiEtfDataExtractionService $aiEtfDataExtractionService,
        ProcessAiEtfDataExtractionService $processAiEtfDataExtractionService
    ): int {
        $query = Etf::query()->orderBy('symbol');

        if ($this->option('symbol')) {
            $query->where('symbol', strtoupper($this->option('symbol')));
        }

        if ($this->option('limit')) {
            $query->limit((int) $this->option('limit'));
        }

        $etfs = $query->get();

        if ($etfs->isEmpty()) {
            $this->warn('No ETFs found for AI extraction.');

            return self::SUCCESS;
        }

        $successCount = 0;
        $failureCount = 0;

        $this->info("Starting AI ETF extraction for {$etfs->count()} ETF(s).");

        foreach ($etfs as $etf) {
            try {
                $this->line("Processing {$etf->symbol}...");

                $extraction = $aiEtfDataExtractionService->extract($etf);

                $processAiEtfDataExtractionService->process($extraction);

                $successCount++;

                $this->info("Processed {$etf->symbol} successfully.");
            } catch (\Throwable $e) {
                $failureCount++;

                Log::error('AI ETF extraction command failed for ETF.', [
                    'etf_id' => $etf->id,
                    'symbol' => $etf->symbol,
                    'message' => $e->getMessage(),
                    'exception' => $e,
                ]);

                $this->error("Failed processing {$etf->symbol}: {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info('AI ETF extraction complete.');
        $this->info("Successful: {$successCount}");
        $this->info("Failed: {$failureCount}");

        return $failureCount > 0 ? self::FAILURE : self::SUCCESS;
    }
}