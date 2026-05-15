<?php

namespace App\Services\EtfComparisons;

use InvalidArgumentException;

class EtfComparisonService
{
    public function getConfig(): array
    {
        return config('etf_comparison');
    }

    public function getMetrics(): array
    {
        return config('etf_comparison.metrics', []);
    }

    public function getMetric(string $metric): array
    {
        $metrics = $this->getMetrics();

        if (! array_key_exists($metric, $metrics)) {
            throw new InvalidArgumentException("Invalid ETF comparison metric [{$metric}].");
        }

        return $metrics[$metric];
    }

    public function getRanges(): array
    {
        return config('etf_comparison.ranges', []);
    }

    public function getRange(string $range): int
    {
        $ranges = $this->getRanges();

        if (! array_key_exists($range, $ranges)) {
            throw new InvalidArgumentException("Invalid ETF comparison range [{$range}].");
        }

        return $ranges[$range];
    }

    public function getDefaults(): array
    {
        return config('etf_comparison.defaults', []);
    }

    public function getDefaultMetric(): string
    {
        return $this->getDefaults()['metric'];
    }

    public function getDefaultRange(): string
    {
        return $this->getDefaults()['range'];
    }

    public function getMaxEtfs(): int
    {
        return $this->getDefaults()['max_etfs'];
    }

    public function resolveMetric(?string $metric): string
    {
        $metric = $metric ?: $this->getDefaultMetric();

        $this->getMetric($metric);

        return $metric;
    }

    public function resolveRange(?string $range): string
    {
        $range = $range ?: $this->getDefaultRange();

        $this->getRange($range);

        return $range;
    }

    public function resolveEtfIds(array|string|null $etfIds): array
    {
        if (is_null($etfIds)) {
            throw new InvalidArgumentException('At least one ETF is required for comparison.');
        }

        if (is_string($etfIds)) {
            $etfIds = explode(',', $etfIds);
        }

        $etfIds = collect($etfIds)
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->values()
            ->toArray();

        if (empty($etfIds)) {
            throw new InvalidArgumentException('At least one valid ETF is required for comparison.');
        }

        if (count($etfIds) > $this->getMaxEtfs()) {
            throw new InvalidArgumentException("You may compare up to {$this->getMaxEtfs()} ETFs at one time.");
        }

        return $etfIds;
    }

    public function resolve(array $input = []): array
    {
        $metric = $this->resolveMetric($input['metric'] ?? null);
        $range = $this->resolveRange($input['range'] ?? null);
        $etfIds = $this->resolveEtfIds($input['etf_ids'] ?? null);

        $metricConfig = $this->getMetric($metric);
        $days = $this->getRange($range);

        return [
            'metric' => $metric,
            'range' => $range,
            'days' => $days,
            'etf_ids' => $etfIds,
            'metric_config' => $metricConfig,
            'table' => $metricConfig['table'],
            'date_column' => $metricConfig['date_column'],
            'value_column' => $metricConfig['value_column'],
        ];
    }

    public function getOptions(): array
    {
        return [
            'metrics' => $this->getMetrics(),
            'ranges' => $this->getRanges(),
            'defaults' => $this->getDefaults(),
        ];
    }
}