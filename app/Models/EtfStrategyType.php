<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EtfStrategyType extends Model
{
    /** @use HasFactory<\Database\Factories\EtfFactory> */
    use HasFactory;

    const COVERED_CALL = 1;
    const SYNTHETIC_COVERED_CALL = 2;
    const LEVERAGED_COVERED_CALL = 3;
    const SINGLE_STOCK_COVERED_CALL = 4;
    const ZERO_DTE_COVERED_CALL = 5;
    const OPTION_INCOME = 6;
    const LEVERAGED_ETF = 7;
    const INVERSE_ETF = 8;
    const TREASURY_INCOME = 9;
    const DIVIDEND_GROWTH = 10;
    const HIGH_YIELD_INCOME = 11;
    const BUY_WRITE = 12;
    const COLLAR_STRATEGY = 13;
    const BUFFER_ETF = 14;
    const TARGET_INCOME = 15;

    protected $fillable = [

        'etf_strategy_type_name'

    ];

    protected function casts(): array
    {

        return [

            'created_at' => 'date:Y-m-d',
            'updated_at' => 'date:Y-m-d'

        ];
    }
}
