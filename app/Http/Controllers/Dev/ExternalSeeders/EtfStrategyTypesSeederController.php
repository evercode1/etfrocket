<?php

namespace App\Http\Controllers\Dev\ExternalSeeders;

use App\Http\Controllers\Controller;
use App\Models\EtfStrategyType;
use Illuminate\Support\Facades\DB;

class EtfStrategyTypesSeederController extends Controller
{

    public function run(): void
    {
        DB::table('etf_strategy_types')->truncate();

        $strategyTypes = [

            'Covered Call',
            'Synthetic Covered Call',
            'Leveraged Covered Call',
            'Single Stock Covered Call',
            '0DTE Covered Call',
            'Option Income',
            'Leveraged ETF',
            'Inverse ETF',
            'Treasury Income',
            'Dividend Growth',
            'High Yield Income',
            'Buy Write',
            'Collar Strategy',
            'Buffer ETF',
            'Target Income',

        ];

        foreach ($strategyTypes as $strategyTypeName) {

            EtfStrategyType::create([

                'etf_strategy_type_name' => $strategyTypeName,

            ]);
        }
    }
}
