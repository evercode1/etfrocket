<?php

namespace Tests\Unit\AiExtraction;

use App\Models\AiDataExtraction;
use App\Models\Etf;
use App\Services\AI\Extractions\AiEtfDataExtractionService;
use Database\Seeders\DataSourceSeeder;
use Database\Seeders\DistributionFrequencySeeder;
use Database\Seeders\EtfIssuerSeeder;
use Database\Seeders\EtfSeeder;
use Database\Seeders\EtfStrategyTypeSeeder;
use Database\Seeders\StatusSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AiEtfDataExtractionServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        DB::table('ai_data_extractions')->truncate();
        DB::table('etfs')->truncate();
        DB::table('data_sources')->truncate();
        DB::table('distribution_frequencies')->truncate();
        DB::table('etf_strategy_types')->truncate();
        DB::table('etf_issuers')->truncate();
        DB::table('statuses')->truncate();

        $this->seed(StatusSeeder::class);
        $this->seed(DataSourceSeeder::class);
        $this->seed(EtfIssuerSeeder::class);
        $this->seed(EtfStrategyTypeSeeder::class);
        $this->seed(DistributionFrequencySeeder::class);
        $this->seed(EtfSeeder::class);

        config([
            'services.openai.model' => env('OPENAI_MODEL', 'gpt-4.1-mini'),
        ]);
    }

    protected function tearDown(): void
    {
        DB::table('ai_data_extractions')->truncate();
        DB::table('etfs')->truncate();
        DB::table('data_sources')->truncate();
        DB::table('distribution_frequencies')->truncate();
        DB::table('etf_strategy_types')->truncate();
        DB::table('etf_issuers')->truncate();
        DB::table('statuses')->truncate();

        parent::tearDown();
    }

    public function test_it_sends_prompt_to_openai_and_stores_extracted_data()
    {

        config([
            'services.openai.api_key' => 'test-api-key',
            'services.openai.model' => 'gpt-4.1-mini',
        ]);
        
        Http::fake([
            'https://api.openai.com/v1/responses' => Http::response([
                'output' => [
                    [
                        'content' => [
                            [
                                'text' => json_encode([
                                    'symbol' => 'LFGY',
                                    'source_url' => 'https://yieldmaxetfs.com/our-etfs/lfgy/',
                                    'price' => [
                                        'close_price' => 12.52,
                                        'price_date' => '2026-05-14',
                                        'volume' => 1234567,
                                    ],
                                    'nav' => [
                                        'nav_per_share' => 12.34,
                                        'as_of_date' => '2026-05-14',
                                    ],
                                    'aum' => [
                                        'assets_under_management' => 123456789,
                                        'as_of_date' => '2026-05-14',
                                    ],
                                    'dividend' => [
                                        'dividend_amount' => 0.2635,
                                        'ex_dividend_date' => '2026-05-14',
                                        'payment_date' => '2026-05-16',
                                    ],
                                ]),
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $etf = Etf::where('symbol', 'LFGY')->firstOrFail();

        $extraction = (new AiEtfDataExtractionService)->extract($etf);

        $this->assertInstanceOf(AiDataExtraction::class, $extraction);

        $this->assertEquals($etf->id, $extraction->etf_id);
        $this->assertEquals($etf->website_url, $extraction->source_url);
        $this->assertFalse((bool) $extraction->is_validated);
        $this->assertNotNull($extraction->processed_at);

        $this->assertEquals('LFGY', $extraction->extracted_data['symbol']);
        $this->assertEquals(12.52, $extraction->extracted_data['price']['close_price']);
        $this->assertEquals(12.34, $extraction->extracted_data['nav']['nav_per_share']);
        $this->assertEquals(123456789, $extraction->extracted_data['aum']['assets_under_management']);
        $this->assertEquals(0.2635, $extraction->extracted_data['dividend']['dividend_amount']);

        $this->assertDatabaseHas('ai_data_extractions', [
            'id' => $extraction->id,
            'etf_id' => $etf->id,
            'source_url' => $etf->website_url,
            'is_validated' => 0,
        ]);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.openai.com/v1/responses'
                && $request->hasHeader('Authorization', 'Bearer test-api-key')
                && $request['model'] === 'gpt-4.1-mini'
                && $request['text']['format']['type'] === 'json_schema';
        });
    }

    public function test_it_throws_exception_when_openai_request_fails()
    {
        Http::fake([
            'https://api.openai.com/v1/responses' => Http::response([
                'error' => [
                    'message' => 'API failure',
                ],
            ], 500),
        ]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('AI ETF extraction failed.');

        $etf = Etf::where('symbol', 'LFGY')->firstOrFail();

        (new AiEtfDataExtractionService)->extract($etf);
    }

    public function test_it_throws_exception_when_openai_returns_invalid_json()
    {
        Http::fake([
            'https://api.openai.com/v1/responses' => Http::response([
                'output' => [
                    [
                        'content' => [
                            [
                                'text' => 'not valid json',
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('AI ETF extraction returned invalid JSON.');

        $etf = Etf::where('symbol', 'LFGY')->firstOrFail();

        (new AiEtfDataExtractionService)->extract($etf);
    }

    public function test_it_can_call_real_openai_for_etf_extraction()
    {
        if (! env('RUN_OPENAI_TESTS')) {
            $this->markTestSkipped('OpenAI integration tests are disabled.');
        }



        $etf = Etf::where('symbol', 'LFGY')->firstOrFail();

        $extraction = (new AiEtfDataExtractionService)->extract($etf);

        $this->assertNotNull($extraction->id);
        $this->assertEquals('LFGY', $extraction->extracted_data['symbol']);
        $this->assertArrayHasKey('price', $extraction->extracted_data);
        $this->assertArrayHasKey('nav', $extraction->extracted_data);
        $this->assertArrayHasKey('aum', $extraction->extracted_data);
        $this->assertArrayHasKey('dividend', $extraction->extracted_data);
    }
}
