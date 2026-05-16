<?php

namespace App\Services\Imports;

use App\Imports\EtfPriceHistoryImport;
use App\Models\Etf;
use App\Models\EtfPriceHistory;
use Illuminate\Support\Facades\DB;

class ImportEtfPriceHistoryService
{
    public function import(int $etfId, string $filePath): array
    {
        $etf = Etf::findOrFail($etfId);

        $rows = (new EtfPriceHistoryImport())->parse($filePath);

        /*
        |--------------------------------------------------------------------------
        | Ensure chronological import order
        |--------------------------------------------------------------------------
        */

        $rows = collect($rows)

            ->sortBy('price_date')

            ->values()

            ->toArray();

        return DB::transaction(function () use ($etf, $rows) {


            /*
            |--------------------------------------------------------------------------
            | Replace existing ETF price history
            |--------------------------------------------------------------------------
            */

            $deletedRows = EtfPriceHistory::where('etf_id', $etf->id)
                ->delete();

            /*
            |--------------------------------------------------------------------------
            | Import fresh history
            |--------------------------------------------------------------------------
            */

            foreach ($rows as $row) {

                EtfPriceHistory::create([

                    'etf_id' => $etf->id,

                    'price_date' => $row['price_date'],

                    'close_price' => $row['close_price'],

                    'volume' => $row['volume'],

                    'data_source_id' => 1,

                ]);
            }

            return [

                'etf_id' => $etf->id,

                'symbol' => $etf->symbol,

                'rows_imported' => count($rows),

                'rows_deleted' => $deletedRows,

                'start_date' => $rows[0]['price_date'] ?? null,

                'end_date' => $rows[count($rows) - 1]['price_date'] ?? null,

            ];
        });
    }
}
