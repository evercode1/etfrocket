<?php

namespace App\Services\PortfolioTransactions;

use App\Models\Portfolio;
use App\Models\PortfolioTransaction;
use Illuminate\Database\Eloquent\Collection;

class ListPortfolioTransactionsService
{
    public function getData(
        int $userId,
        int $portfolioId,
        ?int $etfId = null
    ): Collection {

        Portfolio::where('user_id', $userId)
            ->where('id', $portfolioId)
            ->firstOrFail();

        $query = PortfolioTransaction::where('portfolio_id', $portfolioId);

        if ($etfId) {
            $query->where('etf_id', $etfId);
        }

        return $query
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->get();
    }
}