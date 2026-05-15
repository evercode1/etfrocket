<?php

namespace Database\Factories;

use App\Models\PortfolioTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PortfolioTransaction>
 */
class PortfolioTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'portfolio_id' => 1,

            'etf_id' => 1,

            'transaction_type_id' => 1,

            'shares' => $this->faker->randomFloat(4, 1, 1000),

            'price_per_share' => $this->faker->randomFloat(4, 10, 500),

            'transaction_date' => $this->faker->date(),

        ];
    }
}
