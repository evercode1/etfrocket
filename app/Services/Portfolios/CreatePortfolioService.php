<?php

namespace App\Services\Portfolios;

use App\Models\Portfolio;
use Illuminate\Support\Facades\DB;

class CreatePortfolioService
{
    public function create(int $userId, array $data): Portfolio
    {
        return DB::transaction(function () use ($userId, $data) {

            $isDefault = (bool) ($data['is_default'] ?? false);

            if ($isDefault) {
                Portfolio::where('user_id', $userId)
                    ->update(['is_default' => false]);
            }

            return Portfolio::create([
                'user_id' => $userId,
                'status_id' => 4,
                'portfolio_name' => $data['portfolio_name'],
                'is_default' => $isDefault,
            ]);
        });
    }
}