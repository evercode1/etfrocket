<?php

namespace App\Services\Portfolios;

use App\Models\Portfolio;

class ViewPortfolioService
{
    public function getData(int $userId, int $portfolioId): Portfolio
    {
        return Portfolio::where('user_id', $userId)
            ->where('id', $portfolioId)
            ->firstOrFail();
    }
}