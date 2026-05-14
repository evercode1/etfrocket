<?php

namespace Database\Seeders;

use App\Models\Etf;
use App\Models\EtfDividendHistory;
use App\Models\DataSource;
use Illuminate\Database\Seeder;

class EtfDividendHistorySeeder extends Seeder
{
    public function run(): void
    {
        $data_source_id = DataSource::MANUAL_ENTRY;

        EtfDividendHistory::truncate();

        $rows = [
            'CHPY' => [
                ['amount' => 0.3860, 'ex_date' => '2025-05-29', 'payment_date' => '2025-05-30', 'as_of' => '2025-06-01'],
                ['amount' => 0.3767, 'ex_date' => '2025-06-26', 'payment_date' => '2025-06-27', 'as_of' => '2025-07-01'],
                ['amount' => 0.4151, 'ex_date' => '2025-07-31', 'payment_date' => '2025-08-01', 'as_of' => '2025-08-01'],
                ['amount' => 0.3964, 'ex_date' => '2025-08-28', 'payment_date' => '2025-08-29', 'as_of' => '2025-09-01'],
                ['amount' => 0.4125, 'ex_date' => '2025-09-25', 'payment_date' => '2025-09-26', 'as_of' => '2025-10-01'],
                ['amount' => 0.4634, 'ex_date' => '2025-10-29', 'payment_date' => '2025-10-30', 'as_of' => '2025-11-01'],
                ['amount' => 0.4394, 'ex_date' => '2025-11-26', 'payment_date' => '2025-11-28', 'as_of' => '2025-12-01'],
                ['amount' => 0.4401, 'ex_date' => '2025-12-31', 'payment_date' => '2026-01-02', 'as_of' => '2026-01-01'],
                ['amount' => 0.4826, 'ex_date' => '2026-01-28', 'payment_date' => '2026-01-29', 'as_of' => '2026-02-01'],
                ['amount' => 0.4686, 'ex_date' => '2026-02-25', 'payment_date' => '2026-02-26', 'as_of' => '2026-03-01'],
                ['amount' => 0.4089, 'ex_date' => '2026-04-01', 'payment_date' => '2026-04-02', 'as_of' => '2026-04-01'],
                ['amount' => 0.6041, 'ex_date' => '2026-04-29', 'payment_date' => '2026-04-30', 'as_of' => '2026-05-01'],
            ],

            'AMDY' => [
                ['amount' => 2.1165, 'ex_date' => '2025-05-29', 'payment_date' => '2025-05-30', 'as_of' => '2025-06-01'],
                ['amount' => 2.3145, 'ex_date' => '2025-06-26', 'payment_date' => '2025-06-27', 'as_of' => '2025-07-01'],
                ['amount' => 2.8280, 'ex_date' => '2025-07-24', 'payment_date' => '2025-07-25', 'as_of' => '2025-08-01'],
                ['amount' => 2.7205, 'ex_date' => '2025-08-21', 'payment_date' => '2025-08-22', 'as_of' => '2025-09-01'],
                ['amount' => 1.2205, 'ex_date' => '2025-09-18', 'payment_date' => '2025-09-19', 'as_of' => '2025-10-01'],
                ['amount' => 1.1605, 'ex_date' => '2025-10-30', 'payment_date' => '2025-10-31', 'as_of' => '2025-11-01'],
                ['amount' => 0.5485, 'ex_date' => '2025-11-28', 'payment_date' => '2025-12-01', 'as_of' => '2025-12-01'],
                ['amount' => 0.4118, 'ex_date' => '2025-12-26', 'payment_date' => '2025-12-29', 'as_of' => '2026-01-01'],
                ['amount' => 0.5465, 'ex_date' => '2026-01-29', 'payment_date' => '2026-01-30', 'as_of' => '2026-02-01'],
                ['amount' => 0.3038, 'ex_date' => '2026-02-26', 'payment_date' => '2026-02-27', 'as_of' => '2026-03-01'],
                ['amount' => 0.3288, 'ex_date' => '2026-03-26', 'payment_date' => '2026-03-27', 'as_of' => '2026-04-01'],
                ['amount' => 0.9448, 'ex_date' => '2026-04-30', 'payment_date' => '2026-05-01', 'as_of' => '2026-05-01'],
            ],

            'GOOY' => [
                ['amount' => 0.3449, 'ex_date' => '2025-05-15', 'payment_date' => '2025-05-16', 'as_of' => '2025-06-01'],
                ['amount' => 0.3978, 'ex_date' => '2025-06-12', 'payment_date' => '2025-06-13', 'as_of' => '2025-07-01'],
                ['amount' => 0.3077, 'ex_date' => '2025-07-10', 'payment_date' => '2025-07-11', 'as_of' => '2025-08-01'],
                ['amount' => 0.4491, 'ex_date' => '2025-08-07', 'payment_date' => '2025-08-08', 'as_of' => '2025-09-01'],
                ['amount' => 0.6942, 'ex_date' => '2025-09-04', 'payment_date' => '2025-09-05', 'as_of' => '2025-10-01'],
                ['amount' => 0.1383, 'ex_date' => '2025-10-30', 'payment_date' => '2025-10-31', 'as_of' => '2025-11-01'],
                ['amount' => 0.2278, 'ex_date' => '2025-11-28', 'payment_date' => '2025-12-01', 'as_of' => '2025-12-01'],
                ['amount' => 0.0869, 'ex_date' => '2025-12-26', 'payment_date' => '2025-12-29', 'as_of' => '2026-01-01'],
                ['amount' => 0.1032, 'ex_date' => '2026-01-29', 'payment_date' => '2026-01-30', 'as_of' => '2026-02-01'],
                ['amount' => 0.1048, 'ex_date' => '2026-02-26', 'payment_date' => '2026-02-27', 'as_of' => '2026-03-01'],
                ['amount' => 0.0818, 'ex_date' => '2026-03-26', 'payment_date' => '2026-03-27', 'as_of' => '2026-04-01'],
                ['amount' => 0.2247, 'ex_date' => '2026-04-30', 'payment_date' => '2026-05-01', 'as_of' => '2026-05-01'],
            ],

            'QQQI' => [
                ['amount' => 0.6374, 'ex_date' => '2025-05-21', 'payment_date' => '2025-05-23', 'as_of' => '2025-06-01'],
                ['amount' => 0.6282, 'ex_date' => '2025-06-25', 'payment_date' => '2025-06-27', 'as_of' => '2025-07-01'],
                ['amount' => 0.6366, 'ex_date' => '2025-07-23', 'payment_date' => '2025-07-25', 'as_of' => '2025-08-01'],
                ['amount' => 0.6285, 'ex_date' => '2025-08-20', 'payment_date' => '2025-08-22', 'as_of' => '2025-09-01'],
                ['amount' => 0.6411, 'ex_date' => '2025-09-24', 'payment_date' => '2025-09-26', 'as_of' => '2025-10-01'],
                ['amount' => 0.6445, 'ex_date' => '2025-10-22', 'payment_date' => '2025-10-24', 'as_of' => '2025-11-01'],
                ['amount' => 0.6304, 'ex_date' => '2025-11-26', 'payment_date' => '2025-11-28', 'as_of' => '2025-12-01'],
                ['amount' => 0.64129, 'ex_date' => '2025-12-24', 'payment_date' => '2025-12-26', 'as_of' => '2026-01-01'],
                ['amount' => 0.6359, 'ex_date' => '2026-01-21', 'payment_date' => '2026-01-23', 'as_of' => '2026-02-01'],
                ['amount' => 0.6140, 'ex_date' => '2026-02-18', 'payment_date' => '2026-02-20', 'as_of' => '2026-03-01'],
                ['amount' => 0.6089, 'ex_date' => '2026-03-18', 'payment_date' => '2026-03-20', 'as_of' => '2026-04-01'],
                ['amount' => 0.6297, 'ex_date' => '2026-04-22', 'payment_date' => '2026-04-24', 'as_of' => '2026-05-01'],
            ],

            'NVII' => [
                ['amount' => 0.15571, 'ex_date' => '2025-07-01', 'payment_date' => '2025-07-02', 'as_of' => '2025-07-01'],
                ['amount' => 0.22846, 'ex_date' => '2025-07-29', 'payment_date' => '2025-07-30', 'as_of' => '2025-08-01'],
                ['amount' => 0.2152, 'ex_date' => '2025-08-26', 'payment_date' => '2025-08-27', 'as_of' => '2025-09-01'],
                ['amount' => 0.4578, 'ex_date' => '2025-09-30', 'payment_date' => '2025-10-01', 'as_of' => '2025-10-01'],
                ['amount' => 0.2711, 'ex_date' => '2025-10-28', 'payment_date' => '2025-10-29', 'as_of' => '2025-11-01'],
                ['amount' => 0.5843, 'ex_date' => '2025-11-25', 'payment_date' => '2025-11-26', 'as_of' => '2025-12-01'],
                ['amount' => 0.1724, 'ex_date' => '2025-12-30', 'payment_date' => '2025-12-31', 'as_of' => '2026-01-01'],
                ['amount' => 0.1752, 'ex_date' => '2026-01-27', 'payment_date' => '2026-01-28', 'as_of' => '2026-02-01'],
                ['amount' => 0.2407, 'ex_date' => '2026-02-24', 'payment_date' => '2026-02-25', 'as_of' => '2026-03-01'],
                ['amount' => 0.1817, 'ex_date' => '2026-03-31', 'payment_date' => '2026-04-01', 'as_of' => '2026-04-01'],
                ['amount' => 0.1795, 'ex_date' => '2026-04-28', 'payment_date' => '2026-04-29', 'as_of' => '2026-05-01'],
            ],

            'BLOX' => [
                ['amount' => 0.1559, 'ex_date' => '2025-08-01', 'payment_date' => '2025-08-04', 'as_of' => '2025-08-01'],
                ['amount' => 0.1371, 'ex_date' => '2025-08-29', 'payment_date' => '2025-09-02', 'as_of' => '2025-09-01'],
                ['amount' => 0.1641, 'ex_date' => '2025-09-26', 'payment_date' => '2025-09-29', 'as_of' => '2025-10-01'],
                ['amount' => 0.1845, 'ex_date' => '2025-10-31', 'payment_date' => '2025-11-03', 'as_of' => '2025-11-01'],
                ['amount' => 0.1295, 'ex_date' => '2025-11-28', 'payment_date' => '2025-12-01', 'as_of' => '2025-12-01'],
                ['amount' => 0.1285, 'ex_date' => '2025-12-26', 'payment_date' => '2025-12-29', 'as_of' => '2026-01-01'],
                ['amount' => 0.1326, 'ex_date' => '2026-01-30', 'payment_date' => '2026-02-02', 'as_of' => '2026-02-01'],
                ['amount' => 0.1065, 'ex_date' => '2026-02-27', 'payment_date' => '2026-03-02', 'as_of' => '2026-03-01'],
                ['amount' => 0.0977, 'ex_date' => '2026-03-27', 'payment_date' => '2026-03-30', 'as_of' => '2026-04-01'],
                ['amount' => 0.1073, 'ex_date' => '2026-05-01', 'payment_date' => '2026-05-04', 'as_of' => '2026-05-01'],
            ],

            'LFGY' => [
                ['amount' => 0.4906, 'ex_date' => '2025-05-29', 'payment_date' => '2025-05-30', 'as_of' => '2025-06-01'],
                ['amount' => 0.4836, 'ex_date' => '2025-06-26', 'payment_date' => '2025-06-27', 'as_of' => '2025-07-01'],
                ['amount' => 0.4737, 'ex_date' => '2025-07-31', 'payment_date' => '2025-08-01', 'as_of' => '2025-08-01'],
                ['amount' => 0.4470, 'ex_date' => '2025-08-28', 'payment_date' => '2025-08-29', 'as_of' => '2025-09-01'],
                ['amount' => 0.5120, 'ex_date' => '2025-09-25', 'payment_date' => '2025-09-26', 'as_of' => '2025-10-01'],
                ['amount' => 0.5871, 'ex_date' => '2025-10-29', 'payment_date' => '2025-10-30', 'as_of' => '2025-11-01'],
                ['amount' => 0.3673, 'ex_date' => '2025-11-26', 'payment_date' => '2025-11-28', 'as_of' => '2025-12-01'],
                ['amount' => 0.2481, 'ex_date' => '2025-12-31', 'payment_date' => '2026-01-02', 'as_of' => '2026-01-01'],
                ['amount' => 0.2741, 'ex_date' => '2026-01-28', 'payment_date' => '2026-01-29', 'as_of' => '2026-02-01'],
                ['amount' => 0.2209, 'ex_date' => '2026-02-25', 'payment_date' => '2026-02-26', 'as_of' => '2026-03-01'],
                ['amount' => 0.2033, 'ex_date' => '2026-04-01', 'payment_date' => '2026-04-02', 'as_of' => '2026-04-01'],
                ['amount' => 0.2228, 'ex_date' => '2026-04-29', 'payment_date' => '2026-04-30', 'as_of' => '2026-05-01'],
            ],
        ];

        foreach ($rows as $symbol => $dividends) {
            $etf = Etf::where('symbol', $symbol)->first();

            if (! $etf) {
                continue;
            }

            foreach ($dividends as $dividend) {
                EtfDividendHistory::updateOrCreate(
                    [
                        'etf_id' => $etf->id,
                        'ex_dividend_date' => $dividend['ex_date'],
                    ],
                    [
                        'dividend_amount' => $dividend['amount'],
                        'payment_date' => $dividend['payment_date'],
                        'data_source_id' => $data_source_id,
                        'source_as_of_date' => $dividend['as_of'],
                        'retrieved_at' => now(),
                    ]
                );
            }
        }
    }
}