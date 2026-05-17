<?php

namespace App\Http\Controllers\Admin\Support;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserStatsController extends Controller
{
    public function userSignupStats(Request $request)
    {
        $range = $request->input('range', '7d');

        $allowedRanges = [
            '1d',
            '7d',
            '30d',
            '90d',
            '1y',
            'max',
        ];

        if (! in_array($range, $allowedRanges)) {
            $range = '7d';
        }

        [$startDate, $endDate, $groupFormat, $labelFormat] = $this->getRangeConfig($range);

        $signupQuery = User::query();

        if ($startDate) {
            $signupQuery->where('created_at', '>=', $startDate);
        }

        $signupRows = $signupQuery
            ->select([
                DB::raw("DATE_FORMAT(created_at, '{$groupFormat}') as period"),
                DB::raw('COUNT(*) as total'),
            ])
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->keyBy('period');

        $verifiedQuery = User::query()
            ->whereNotNull('email_verified_at');

        if ($startDate) {
            $verifiedQuery->where('created_at', '>=', $startDate);
        }

        $verifiedRows = $verifiedQuery
            ->select([
                DB::raw("DATE_FORMAT(created_at, '{$groupFormat}') as period"),
                DB::raw('COUNT(*) as total'),
            ])
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->keyBy('period');

        $chartData = $this->buildChartData(
            $range,
            $startDate,
            $endDate,
            $groupFormat,
            $labelFormat,
            $signupRows,
            $verifiedRows
        );

        return response()->json([
            'status' => 'success',
            'range' => $range,
            'total_users' => User::count(),
            'total_verified_users' => User::whereNotNull('email_verified_at')->count(),
            'range_total' => collect($chartData)->sum('signups'),
            'range_verified_total' => collect($chartData)->sum('verified'),
            'data' => $chartData,
            'available_ranges' => $allowedRanges,
        ], 200);
    }

    private function getRangeConfig(string $range): array
    {
        $endDate = now();

        return match ($range) {
            '1d' => [
                now()->subDay()->startOfHour(),
                $endDate,
                '%Y-%m-%d %H:00:00',
                'M j, g A',
            ],

            '7d' => [
                now()->subDays(6)->startOfDay(),
                $endDate,
                '%Y-%m-%d',
                'M j',
            ],

            '30d' => [
                now()->subDays(29)->startOfDay(),
                $endDate,
                '%Y-%m-%d',
                'M j',
            ],

            '90d' => [
                now()->subDays(89)->startOfDay(),
                $endDate,
                '%Y-%m-%d',
                'M j',
            ],

            'max' => [
                null,
                $endDate,
                '%Y-%m',
                'M Y',
            ],

            default => [
                now()->subYear()->startOfMonth(),
                $endDate,
                '%Y-%m',
                'M Y',
            ],
        };
    }

    private function buildChartData(
        string $range,
        ?Carbon $startDate,
        Carbon $endDate,
        string $groupFormat,
        string $labelFormat,
        $signupRows,
        $verifiedRows
    ): array {
        if ($range === 'max') {
            $firstUserDate = User::min('created_at');

            if (! $firstUserDate) {
                return [];
            }

            $startDate = Carbon::parse($firstUserDate)->startOfMonth();
        }

        $date = $startDate->copy();

        $chartData = [];

        while ($date <= $endDate) {
            $period = $this->formatPeriodKey($date, $groupFormat);

            $chartData[] = [
                'period' => $period,
                'label' => $date->format($labelFormat),
                'signups' => (int) optional($signupRows->get($period))->total,
                'verified' => (int) optional($verifiedRows->get($period))->total,
            ];

            if ($range === '1d') {
                $date->addHour();
            } elseif (in_array($range, ['7d', '30d', '90d'])) {
                $date->addDay();
            } else {
                $date->addMonth();
            }
        }

        return $chartData;
    }

    private function formatPeriodKey(Carbon $date, string $groupFormat): string
    {
        return match ($groupFormat) {
            '%Y-%m-%d %H:00:00' => $date->format('Y-m-d H:00:00'),
            '%Y-%m-%d' => $date->format('Y-m-d'),
            '%Y-%m' => $date->format('Y-m'),
            default => $date->format('Y-m-d'),
        };
    }
}
