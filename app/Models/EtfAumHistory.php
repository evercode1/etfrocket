<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtfAumHistory extends Model
{
    /** @use HasFactory<\Database\Factories\EtfAumHistoryFactory> */
    use HasFactory;

    protected $fillable = [

        'etf_id',
        'aum_date',
        'assets_under_management',
        'source_id',
        'source_as_of_date',
        'retrieved_at'

    ];

    protected function casts(): array
    {

        return [

            'created_at' => 'date:Y-m-d',
            'updated_at' => 'date:Y-m-d',
            'aum_date' => 'date:Y-m-d',
            'source_as_of_date' => 'date:Y-m-d',
            'retrieved_at' => 'date:Y-m-d',

        ];
    }
}
