<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\MetricDirection;

class MetricDirectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('metric_directions')->truncate();

        $directions = [

            'Improving',
            'Eroding',
            'Flat',
            'Growing',
            'Shrinking',

        ];

        foreach ($directions as $directionName) {

            MetricDirection::create([

                'metric_direction_name' => $directionName,

            ]);
        }
    }
}
