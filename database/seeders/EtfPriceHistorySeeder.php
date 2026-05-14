<?php

namespace Database\Seeders;

use App\Models\Etf;
use App\Models\EtfPriceHistory;
use App\Models\DataSource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EtfPriceHistorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('etf_price_histories')->truncate();

        $dataSourceId = DataSource::MANUAL_ENTRY;

        $rows = [

            'CHPY' => [
                ['date' => '2025-06-01', 'price' => 50.12, 'volume' => 182_000],
                ['date' => '2025-07-01', 'price' => 50.68, 'volume' => 194_000],
                ['date' => '2025-08-01', 'price' => 51.22, 'volume' => 205_000],
                ['date' => '2025-09-01', 'price' => 50.94, 'volume' => 221_000],
                ['date' => '2025-10-01', 'price' => 50.62, 'volume' => 238_000],
                ['date' => '2025-11-01', 'price' => 50.12, 'volume' => 247_000],
                ['date' => '2025-12-01', 'price' => 49.82, 'volume' => 261_000],
                ['date' => '2026-01-01', 'price' => 49.34, 'volume' => 278_000],
                ['date' => '2026-02-01', 'price' => 48.92, 'volume' => 286_000],
                ['date' => '2026-03-01', 'price' => 48.48, 'volume' => 301_000],
                ['date' => '2026-04-01', 'price' => 48.06, 'volume' => 319_000],
                ['date' => '2026-05-01', 'price' => 47.62, 'volume' => 334_000],
            ],

            'AMDY' => [
                ['date' => '2025-06-01', 'price' => 22.88, 'volume' => 1_820_000],
                ['date' => '2025-07-01', 'price' => 22.14, 'volume' => 1_940_000],
                ['date' => '2025-08-01', 'price' => 21.72, 'volume' => 2_040_000],
                ['date' => '2025-09-01', 'price' => 20.96, 'volume' => 2_210_000],
                ['date' => '2025-10-01', 'price' => 20.24, 'volume' => 2_360_000],
                ['date' => '2025-11-01', 'price' => 19.52, 'volume' => 2_510_000],
                ['date' => '2025-12-01', 'price' => 18.88, 'volume' => 2_640_000],
                ['date' => '2026-01-01', 'price' => 18.22, 'volume' => 2_720_000],
                ['date' => '2026-02-01', 'price' => 17.64, 'volume' => 2_840_000],
                ['date' => '2026-03-01', 'price' => 17.02, 'volume' => 2_910_000],
                ['date' => '2026-04-01', 'price' => 16.38, 'volume' => 3_020_000],
                ['date' => '2026-05-01', 'price' => 15.84, 'volume' => 3_160_000],
            ],

            'GOOY' => [
                ['date' => '2025-06-01', 'price' => 19.14, 'volume' => 410_000],
                ['date' => '2025-07-01', 'price' => 19.04, 'volume' => 428_000],
                ['date' => '2025-08-01', 'price' => 18.96, 'volume' => 447_000],
                ['date' => '2025-09-01', 'price' => 18.82, 'volume' => 462_000],
                ['date' => '2025-10-01', 'price' => 18.66, 'volume' => 481_000],
                ['date' => '2025-11-01', 'price' => 18.48, 'volume' => 492_000],
                ['date' => '2025-12-01', 'price' => 18.34, 'volume' => 505_000],
                ['date' => '2026-01-01', 'price' => 18.18, 'volume' => 519_000],
                ['date' => '2026-02-01', 'price' => 18.02, 'volume' => 533_000],
                ['date' => '2026-03-01', 'price' => 17.92, 'volume' => 544_000],
                ['date' => '2026-04-01', 'price' => 17.76, 'volume' => 561_000],
                ['date' => '2026-05-01', 'price' => 17.58, 'volume' => 577_000],
            ],

            'QQQI' => [
                ['date' => '2025-06-01', 'price' => 50.42, 'volume' => 1_120_000],
                ['date' => '2025-07-01', 'price' => 51.64, 'volume' => 1_180_000],
                ['date' => '2025-08-01', 'price' => 53.12, 'volume' => 1_240_000],
                ['date' => '2025-09-01', 'price' => 54.88, 'volume' => 1_310_000],
                ['date' => '2025-10-01', 'price' => 56.24, 'volume' => 1_390_000],
                ['date' => '2025-11-01', 'price' => 57.66, 'volume' => 1_460_000],
                ['date' => '2025-12-01', 'price' => 59.08, 'volume' => 1_520_000],
                ['date' => '2026-01-01', 'price' => 60.44, 'volume' => 1_580_000],
                ['date' => '2026-02-01', 'price' => 61.52, 'volume' => 1_630_000],
                ['date' => '2026-03-01', 'price' => 62.34, 'volume' => 1_710_000],
                ['date' => '2026-04-01', 'price' => 63.44, 'volume' => 1_790_000],
                ['date' => '2026-05-01', 'price' => 64.62, 'volume' => 1_860_000],
            ],

            'NVII' => [
                ['date' => '2025-07-01', 'price' => 25.12, 'volume' => 128_000],
                ['date' => '2025-08-01', 'price' => 25.88, 'volume' => 139_000],
                ['date' => '2025-09-01', 'price' => 26.74, 'volume' => 151_000],
                ['date' => '2025-10-01', 'price' => 27.92, 'volume' => 164_000],
                ['date' => '2025-11-01', 'price' => 28.66, 'volume' => 177_000],
                ['date' => '2025-12-01', 'price' => 29.44, 'volume' => 189_000],
                ['date' => '2026-01-01', 'price' => 30.18, 'volume' => 203_000],
                ['date' => '2026-02-01', 'price' => 30.94, 'volume' => 217_000],
                ['date' => '2026-03-01', 'price' => 31.62, 'volume' => 229_000],
                ['date' => '2026-04-01', 'price' => 32.28, 'volume' => 244_000],
                ['date' => '2026-05-01', 'price' => 33.04, 'volume' => 258_000],
            ],

            'BLOX' => [
                ['date' => '2025-08-01', 'price' => 25.42, 'volume' => 98_000],
                ['date' => '2025-09-01', 'price' => 26.18, 'volume' => 107_000],
                ['date' => '2025-10-01', 'price' => 27.22, 'volume' => 116_000],
                ['date' => '2025-11-01', 'price' => 28.12, 'volume' => 124_000],
                ['date' => '2025-12-01', 'price' => 28.94, 'volume' => 132_000],
                ['date' => '2026-01-01', 'price' => 29.72, 'volume' => 141_000],
                ['date' => '2026-02-01', 'price' => 30.38, 'volume' => 149_000],
                ['date' => '2026-03-01', 'price' => 31.04, 'volume' => 158_000],
                ['date' => '2026-04-01', 'price' => 31.72, 'volume' => 166_000],
                ['date' => '2026-05-01', 'price' => 32.46, 'volume' => 174_000],
            ],

            'LFGY' => [
                ['date' => '2025-06-01', 'price' => 50.18, 'volume' => 148_000],
                ['date' => '2025-07-01', 'price' => 49.86, 'volume' => 157_000],
                ['date' => '2025-08-01', 'price' => 49.42, 'volume' => 166_000],
                ['date' => '2025-09-01', 'price' => 48.96, 'volume' => 174_000],
                ['date' => '2025-10-01', 'price' => 48.42, 'volume' => 181_000],
                ['date' => '2025-11-01', 'price' => 47.98, 'volume' => 189_000],
                ['date' => '2025-12-01', 'price' => 47.54, 'volume' => 197_000],
                ['date' => '2026-01-01', 'price' => 47.02, 'volume' => 206_000],
                ['date' => '2026-02-01', 'price' => 46.52, 'volume' => 214_000],
                ['date' => '2026-03-01', 'price' => 46.04, 'volume' => 221_000],
                ['date' => '2026-04-01', 'price' => 45.62, 'volume' => 229_000],
                ['date' => '2026-05-01', 'price' => 45.18, 'volume' => 237_000],
            ],

        ];

        foreach ($rows as $symbol => $priceRows) {

            $etf = Etf::where('symbol', $symbol)->first();

            if (! $etf) {
                continue;
            }

            foreach ($priceRows as $row) {

                EtfPriceHistory::updateOrCreate(
                    [
                        'etf_id' => $etf->id,
                        'price_date' => $row['date'],
                    ],
                    [
                        'close_price' => $row['price'],
                        'volume' => $row['volume'],
                        'data_source_id' => $dataSourceId,
                        'retrieved_at' => now(),
                    ]
                );
            }
        }
    }
}