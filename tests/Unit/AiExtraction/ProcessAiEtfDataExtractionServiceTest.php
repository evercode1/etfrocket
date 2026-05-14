<?php

namespace Tests\Unit\AiExtraction;

use App\Models\Etf;
use Carbon\Carbon;
use Database\Seeders\EtfSeeder;
use App\Models\AiDataExtraction;
use App\Models\EtfPriceHistory;
use App\Services\AI\Extractions\ProcessAiEtfDataExtractionService;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProcessAiEtfDataExtractionServiceTest extends TestCase
{
    private Etf $etf;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2026-05-14 12:00:00');

        DB::table('etf_dividend_histories')->truncate();
        DB::table('etf_aum_histories')->truncate();
        DB::table('etf_nav_histories')->truncate();
        DB::table('etf_price_histories')->truncate();
        DB::table('ai_data_extractions')->truncate();
        DB::table('etfs')->truncate();

        $this->seed(EtfSeeder::class);

        $this->etf = Etf::where('symbol', 'CHPY')->firstOrFail();
    }

    protected function tearDown(): void
    {
        DB::table('etf_dividend_histories')->truncate();
        DB::table('etf_aum_histories')->truncate();
        DB::table('etf_nav_histories')->truncate();
        DB::table('etf_price_histories')->truncate();
        DB::table('ai_data_extractions')->truncate();
        DB::table('etfs')->truncate();

        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_it_processes_valid_ai_etf_extraction_data()
    {
        $extraction = AiDataExtraction::factory()->create([
            'etf_id' => $this->etf->id,
            'data_source_id' => 1,
            'failed_at' => now()->subDay(),
            'failure_reason' => 'Previous failure.',
            'extracted_data' => [
                'symbol' => $this->etf->symbol,
                'price' => [
                    'close_price' => 25.123456,
                    'price_date' => '2026-05-13',
                    'volume' => 123456,
                ],
                'nav' => [
                    'nav_per_share' => 25.98765,
                    'as_of_date' => '2026-05-13',
                ],
                'aum' => [
                    'assets_under_management' => 100000000,
                    'as_of_date' => '2026-05-13',
                ],
                'dividend' => [
                    'dividend_amount' => 0.123456,
                    'ex_dividend_date' => '2026-05-13',
                    'payment_date' => '2026-05-20',
                ],
            ],
        ]);

        $result = (new ProcessAiEtfDataExtractionService())->process($extraction);

        $this->assertTrue((bool) $result->is_validated);
        $this->assertNotNull($result->processed_at);
        $this->assertNull($result->failed_at);
        $this->assertNull($result->failure_reason);
        $this->assertEquals('AI extracted ETF data processed successfully.', $result->validation_notes);

        $this->assertDatabaseHas('etf_price_histories', [
            'etf_id' => $this->etf->id,
            'price_date' => '2026-05-13',
            'close_price' => 25.1235,
            'volume' => 123456,
            'data_source_id' => 1,
        ]);

        $this->assertDatabaseHas('etf_nav_histories', [
            'etf_id' => $this->etf->id,
            'nav_date' => '2026-05-13',
            'nav_per_share' => 25.9877,
            'data_source_id' => 1,
        ]);

        $this->assertDatabaseHas('etf_aum_histories', [
            'etf_id' => $this->etf->id,
            'aum_date' => '2026-05-13',
            'assets_under_management' => 100000000,
            'data_source_id' => 1,
        ]);

        $this->assertDatabaseHas('etf_dividend_histories', [
            'etf_id' => $this->etf->id,
            'ex_dividend_date' => '2026-05-13',
            'dividend_amount' => 0.1235,
            'payment_date' => '2026-05-20',
            'data_source_id' => 1,
        ]);
    }

    public function test_it_updates_existing_price_record_instead_of_creating_duplicate()
    {
        EtfPriceHistory::factory()->create([
            'etf_id' => $this->etf->id,
            'price_date' => '2026-05-13',
            'close_price' => 20,
            'volume' => 100,
        ]);

        $extraction = AiDataExtraction::factory()->create([
            'etf_id' => $this->etf->id,
            'data_source_id' => 1,
            'extracted_data' => [
                'symbol' => $this->etf->symbol,
                'price' => [
                    'close_price' => 25.55,
                    'price_date' => '2026-05-13',
                    'volume' => 999,
                ],
            ],
        ]);

        (new ProcessAiEtfDataExtractionService())->process($extraction);

        $this->assertEquals(1, EtfPriceHistory::where('etf_id', $this->etf->id)->count());

        $this->assertDatabaseHas('etf_price_histories', [
            'etf_id' => $this->etf->id,
            'price_date' => '2026-05-13',
            'close_price' => 25.55,
            'volume' => 999,
            'data_source_id' => 1,
        ]);
    }

    public function test_it_creates_new_price_record_when_date_is_unique()
    {
        EtfPriceHistory::factory()->create([
            'etf_id' => $this->etf->id,
            'price_date' => '2026-05-12',
            'close_price' => 20,
        ]);

        $extraction = AiDataExtraction::factory()->create([
            'etf_id' => $this->etf->id,
            'data_source_id' => 1,
            'extracted_data' => [
                'symbol' => $this->etf->symbol,
                'price' => [
                    'close_price' => 25.55,
                    'price_date' => '2026-05-13',
                ],
            ],
        ]);

        (new ProcessAiEtfDataExtractionService())->process($extraction);

        $this->assertEquals(2, EtfPriceHistory::where('etf_id', $this->etf->id)->count());
    }

    public function test_it_fails_when_symbol_does_not_match()
    {
        $extraction = AiDataExtraction::factory()->create([
            'etf_id' => $this->etf->id,
            'extracted_data' => [
                'symbol' => 'SPY',
            ],
        ]);

        $this->assertProcessingFailure(
            $extraction,
            'Extracted symbol does not match ETF symbol.'
        );
    }

    public function test_it_fails_when_extracted_data_is_missing()
    {
        $extraction = AiDataExtraction::factory()->create([
            'etf_id' => $this->etf->id,
            'extracted_data' => null,
        ]);

        $this->assertProcessingFailure(
            $extraction,
            'Extracted data is missing or invalid.'
        );
    }

    public function test_it_fails_when_symbol_is_missing()
    {
        $extraction = AiDataExtraction::factory()->create([
            'etf_id' => $this->etf->id,
            'extracted_data' => [],
        ]);

        $this->assertProcessingFailure(
            $extraction,
            'Extracted symbol is missing.'
        );
    }

    public function test_it_fails_when_price_date_is_stale()
    {
        $extraction = AiDataExtraction::factory()->create([
            'etf_id' => $this->etf->id,
            'extracted_data' => [
                'symbol' => $this->etf->symbol,
                'price' => [
                    'close_price' => 25.55,
                    'price_date' => '2026-05-01',
                ],
            ],
        ]);

        $this->assertProcessingFailure(
            $extraction,
            'price_date is stale.'
        );
    }

    public function test_it_fails_when_nav_date_is_stale()
    {
        $extraction = AiDataExtraction::factory()->create([
            'etf_id' => $this->etf->id,
            'extracted_data' => [
                'symbol' => $this->etf->symbol,
                'nav' => [
                    'nav_per_share' => 25.55,
                    'as_of_date' => '2026-05-01',
                ],
            ],
        ]);

        $this->assertProcessingFailure(
            $extraction,
            'nav_as_of_date is stale.'
        );
    }

    public function test_it_fails_when_aum_date_is_stale()
    {
        $extraction = AiDataExtraction::factory()->create([
            'etf_id' => $this->etf->id,
            'extracted_data' => [
                'symbol' => $this->etf->symbol,
                'aum' => [
                    'assets_under_management' => 100000000,
                    'as_of_date' => '2026-05-01',
                ],
            ],
        ]);

        $this->assertProcessingFailure(
            $extraction,
            'aum_as_of_date is stale.'
        );
    }

    public function test_it_allows_old_dividend_dates_without_stale_failure()
    {
        $extraction = AiDataExtraction::factory()->create([
            'etf_id' => $this->etf->id,
            'data_source_id' => 1,
            'extracted_data' => [
                'symbol' => $this->etf->symbol,
                'dividend' => [
                    'dividend_amount' => 0.123456,
                    'ex_dividend_date' => '2026-04-01',
                    'payment_date' => '2026-04-15',
                ],
            ],
        ]);

        $result = (new ProcessAiEtfDataExtractionService())->process($extraction);

        $this->assertTrue((bool) $result->is_validated);
        $this->assertNull($result->failed_at);
        $this->assertNull($result->failure_reason);

        $this->assertDatabaseHas('etf_dividend_histories', [
            'etf_id' => $this->etf->id,
            'ex_dividend_date' => '2026-04-01',
            'payment_date' => '2026-04-15',
            'dividend_amount' => 0.1235,
            'data_source_id' => 1,
        ]);
    }

    private function assertProcessingFailure(AiDataExtraction $extraction, string $expectedMessage): void
    {
        try {
            (new ProcessAiEtfDataExtractionService())->process($extraction);

            $this->fail('Expected RuntimeException was not thrown.');
        } catch (\RuntimeException $e) {
            $this->assertEquals($expectedMessage, $e->getMessage());
        }

        $extraction->refresh();

        $this->assertFalse((bool) $extraction->is_validated);
        $this->assertNotNull($extraction->failed_at);
        $this->assertEquals($expectedMessage, $extraction->failure_reason);
        $this->assertEquals('AI extracted ETF data failed processing.', $extraction->validation_notes);
    }
}