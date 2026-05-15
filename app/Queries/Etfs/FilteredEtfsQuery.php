<?php

namespace App\Queries\Etfs;

use App\Models\Etf;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class FilteredEtfsQuery
{
    public function getData(array $resolvedFilters, ?int $userId = null): LengthAwarePaginator
    {
        $column = $resolvedFilters['column'];
        $sortDirection = $resolvedFilters['sort_direction'];
        $scope = $resolvedFilters['scope'];
        $days = $resolvedFilters['days'];
        $perPage = $resolvedFilters['per_page'] ?? $resolvedFilters['limit'] ?? 25;

        $query = Etf::query()

            ->select([

                'etfs.id',
                'etfs.symbol',
                'etfs.fund_name',
                'etfs.website_url',

                'etf_metrics.performance_range_type_id',

                'etf_metrics.start_date',
                'etf_metrics.end_date',

                'etf_metrics.start_price',
                'etf_metrics.end_price',
                'etf_metrics.price_change',
                'etf_metrics.price_change_percentage',

                'etf_metrics.dividends_paid',
                'etf_metrics.dividend_count',
                'etf_metrics.average_dividend',

                'etf_metrics.total_return_percentage',

                'etf_metrics.start_nav',
                'etf_metrics.end_nav',
                'etf_metrics.nav_change',
                'etf_metrics.nav_erosion_percentage',
                'etf_metrics.nav_direction_id',

                'etf_metrics.start_aum',
                'etf_metrics.end_aum',
                'etf_metrics.aum_change',
                'etf_metrics.aum_change_percentage',
                'etf_metrics.aum_direction_id',

                'etf_metrics.calculated_at',

            ])

            ->leftJoin('etf_metrics', 'etfs.id', '=', 'etf_metrics.etf_id');

        $this->applyRange($query, $days);

        $this->applyScope($query, $scope, $userId);

        $query->whereNotNull("etf_metrics.{$column}");

        return $query
            ->orderBy("etf_metrics.{$column}", $sortDirection)
            ->paginate($perPage);
    }

    private function applyRange(Builder $query, ?int $days): void
    {
        if (! $days) {
            return;
        }

        $fromDate = Carbon::now()->subDays($days)->toDateString();

        $query->whereDate('etf_metrics.calculated_at', '>=', $fromDate);
    }

    private function applyScope(Builder $query, string $scope, ?int $userId): void
    {
        if ($scope !== 'owned') {
            return;
        }

        if (! $userId) {
            $query->whereRaw('1 = 0');

            return;
        }

        $query->join('portfolio_holdings', function ($join) use ($userId) {
            $join->on('etfs.id', '=', 'portfolio_holdings.etf_id')
                ->where('portfolio_holdings.user_id', '=', $userId)
                ->where('portfolio_holdings.is_active', '=', true);
        });
    }
}
