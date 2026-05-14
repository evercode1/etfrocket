<?php

namespace App\Services\Ai\Extractions;

use App\Models\AiDataExtraction;
use App\Models\Etf;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiEtfDataExtractionService
{
    public function extract(Etf $etf): AiDataExtraction
    {

        $prompt = $this->buildPrompt($etf);

        $response = Http::withToken(config('services.openai.api_key'))
            ->timeout(60)
            ->post('https://api.openai.com/v1/responses', [
                'model' => config('services.openai.model', 'gpt-4.1-mini'),

                'input' => [
                    [
                        'role' => 'system',
                        'content' => 'You extract ETF financial data and return only valid JSON matching the requested schema.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],

                'text' => [
                    'format' => [
                        'type' => 'json_schema',
                        'name' => 'etf_data_extraction',
                        'schema' => $this->schema(),
                        'strict' => true,
                    ],
                ],
            ]);

        if (! $response->successful()) {
            Log::error('OpenAI ETF extraction failed', [
                'etf_id' => $etf->id,
                'symbol' => $etf->symbol,
                'response' => $response->json(),
            ]);

            throw new \RuntimeException('AI ETF extraction failed.');
        }

        $content = $response->json('output.0.content.0.text');

        $extractedData = json_decode($content, true);

        if (! is_array($extractedData)) {
            throw new \RuntimeException('AI ETF extraction returned invalid JSON.');
        }

        return AiDataExtraction::create([
            'etf_id' => $etf->id,
            'data_source_id' => $etf->data_source_id ?? null,
            'source_url' => $etf->website_url,
            'raw_payload' => null,
            'prompt' => $prompt,
            'extracted_data' => $extractedData,
            'is_validated' => false,
            'validation_notes' => null,
            'processed_at' => now(),
        ]);
    }

    private function buildPrompt(Etf $etf): string
    {
        return "
You are extracting current ETF data for Daily ETF Stats.

ETF:
Symbol: {$etf->symbol}
Fund Name: {$etf->fund_name}
Official Website URL: {$etf->website_url}

Find the latest available public data for this ETF from the official issuer page or reliable financial data sources.

Extract the following if available:
- close price
- price date
- volume
- NAV per share
- NAV as-of date
- assets under management
- AUM as-of date
- latest dividend amount
- ex-dividend date
- payment date

Rules:
- Do not guess.
- If a value is not available, return null.
- Dates must be YYYY-MM-DD.
- Numbers must be numeric, not formatted strings.
- Use the most recent available data.
- Include source_url and source_as_of_date where possible.
";
    }

    private function schema(): array
    {
        return [
            'type' => 'object',
            'additionalProperties' => false,
            'required' => [
                'symbol',
                'price',
                'nav',
                'aum',
                'dividend',
                'source_url',
            ],
            'properties' => [
                'symbol' => [
                    'type' => 'string',
                ],

                'source_url' => [
                    'type' => ['string', 'null'],
                ],

                'price' => [
                    'type' => 'object',
                    'additionalProperties' => false,
                    'required' => [
                        'close_price',
                        'price_date',
                        'volume',
                    ],
                    'properties' => [
                        'close_price' => [
                            'type' => ['number', 'null'],
                        ],
                        'price_date' => [
                            'type' => ['string', 'null'],
                        ],
                        'volume' => [
                            'type' => ['integer', 'null'],
                        ],
                    ],
                ],

                'nav' => [
                    'type' => 'object',
                    'additionalProperties' => false,
                    'required' => [
                        'nav_per_share',
                        'as_of_date',
                    ],
                    'properties' => [
                        'nav_per_share' => [
                            'type' => ['number', 'null'],
                        ],
                        'as_of_date' => [
                            'type' => ['string', 'null'],
                        ],
                    ],
                ],

                'aum' => [
                    'type' => 'object',
                    'additionalProperties' => false,
                    'required' => [
                        'assets_under_management',
                        'as_of_date',
                    ],
                    'properties' => [
                        'assets_under_management' => [
                            'type' => ['integer', 'null'],
                        ],
                        'as_of_date' => [
                            'type' => ['string', 'null'],
                        ],
                    ],
                ],

                'dividend' => [
                    'type' => 'object',
                    'additionalProperties' => false,
                    'required' => [
                        'dividend_amount',
                        'ex_dividend_date',
                        'payment_date',
                    ],
                    'properties' => [
                        'dividend_amount' => [
                            'type' => ['number', 'null'],
                        ],
                        'ex_dividend_date' => [
                            'type' => ['string', 'null'],
                        ],
                        'payment_date' => [
                            'type' => ['string', 'null'],
                        ],
                    ],
                ],
            ],
        ];
    }
}