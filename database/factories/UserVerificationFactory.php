<?php

namespace Database\Factories;

use App\Models\UserVerification;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<UserVerification>
 */
class UserVerificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => random_int(1, 100),
            'token' => Str::random(60),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
