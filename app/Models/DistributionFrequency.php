<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DistributionFrequency extends Model
{
    /** @use HasFactory<\Database\Factories\EtfFactory> */
    use HasFactory;

    const DAILY = 1;
    const WEEKLY = 2;
    const BI_WEEKLY = 3;
    const MONTHLY = 4;
    const QUARTERLY = 5;
    const SEMI_ANNUAL = 6;
    const ANNUAL = 7;
    const VARIABLE = 8;
    const NONE = 9;

    protected $fillable = [

        'distribution_frequency_name'

    ];

    protected function casts(): array
    {

        return [

            'created_at' => 'date:Y-m-d',
            'updated_at' => 'date:Y-m-d'

        ];
    }
}
