<?php

namespace App\Services\Imports;

use App\Imports\EtfDividendHistoryImport;
use App\Models\Etf;
use App\Models\EtfDividendHistory;
use Illuminate\Support\Facades\DB;

class ImportEtfDividendHistoryService
{
    public function import(int $etfId, string $filePath): array
    {
        $etf = Etf::findOrFail($etfId);

        $rows = (new EtfDividendHistoryImport())->parse($filePath);

        $rows = collect($rows)
            ->sortBy('ex_dividend_date')
            ->values()
            ->toArray();

        return DB::transaction(function () use ($etf, $rows) {

            $deletedRows = EtfDividendHistory::where('etf_id', $etf->id)
                ->delete();

            foreach ($rows as $row) {

                EtfDividendHistory::create([
                    
                    'etf_id' => $etf->id,
                    'dividend_amount' => $row['dividend_amount'],
                    'ex_dividend_date' => $row['ex_dividend_date'],
                    'payment_date' => $row['payment_date'],
                    'data_source_id' => 1,

                ]);
            }

            return [
                'etf_id' => $etf->id,
                'symbol' => $etf->symbol,
                'rows_deleted' => $deletedRows,
                'rows_imported' => count($rows),
                'start_date' => $rows[0]['ex_dividend_date'] ?? null,
                'end_date' => $rows[count($rows) - 1]['ex_dividend_date'] ?? null,
            ];
        });
    }
}