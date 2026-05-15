<?php

namespace Database\Seeders;

use App\Models\TransactionType;
use Illuminate\Database\Seeder;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        TransactionType::truncate();

        TransactionType::updateOrCreate(
            ['id' => TransactionType::BUY],
            [
                'transaction_type_name' => 'Buy',
                'slug' => 'buy',
            ]
        );

        TransactionType::updateOrCreate(
            ['id' => TransactionType::SELL],
            [
                'transaction_type_name' => 'Sell',
                'slug' => 'sell',
            ]
        );
    }
}
