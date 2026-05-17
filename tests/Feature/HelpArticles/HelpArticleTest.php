<?php

namespace Tests\Feature\HelpArticles;

use App\Models\HelpArticle;
use App\Models\HelpArticleCategory;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class HelpArticleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        DB::table('help_articles')->truncate();
        DB::table('help_article_categories')->truncate();
    }

    protected function tearDown(): void
    {
        DB::table('help_articles')->truncate();
        DB::table('help_article_categories')->truncate();

        parent::tearDown();
    }

    public function test_it_returns_a_published_help_article_by_slug(): void
    {
        $category = HelpArticleCategory::factory()->create([
            'category_name' => 'ETF Data',
            'slug' => 'etf-data',
            'is_active' => 1,
        ]);

        $article = HelpArticle::factory()->create([
            'help_article_category_id' => $category->id,
            'title' => 'Understanding ETF Metrics',
            'slug' => 'understanding-etf-metrics',
            'summary' => 'Learn ETF metrics.',
            'content' => '<p>ETF metrics content.</p>',
            'is_published' => 1,
        ]);

        $response = $this->getJson(
            '/api/help-article/understanding-etf-metrics'
        );

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('article.id', $article->id)
            ->assertJsonPath('article.title', 'Understanding ETF Metrics')
            ->assertJsonPath('article.slug', 'understanding-etf-metrics')
            ->assertJsonPath('article.summary', 'Learn ETF metrics.')
            ->assertJsonPath('article.content', '<p>ETF metrics content.</p>')
            ->assertJsonPath('article.category_name', 'ETF Data')
            ->assertJsonPath('article.category_slug', 'etf-data');
    }

    public function test_it_returns_404_for_unpublished_help_article(): void
    {
        $category = HelpArticleCategory::factory()->create([
            'category_name' => 'Support',
            'slug' => 'support',
            'is_active' => 1,
        ]);

        HelpArticle::factory()->create([
            'help_article_category_id' => $category->id,
            'title' => 'Draft Support Article',
            'slug' => 'draft-support-article',
            'content' => 'Draft content.',
            'is_published' => 0,
        ]);

        $response = $this->getJson(
            '/api/help-article/draft-support-article'
        );

        $response->assertNotFound()
            ->assertJsonPath('status', 'error')
            ->assertJsonPath('message', 'Help article not found.');
    }

    public function test_it_returns_404_for_nonexistent_help_article(): void
    {
        $response = $this->getJson(
            '/api/help-article/nonexistent-article'
        );

        $response->assertNotFound()
            ->assertJsonPath('status', 'error')
            ->assertJsonPath('message', 'Help article not found.');
    }

    public function test_it_returns_category_data_with_article(): void
    {
        $category = HelpArticleCategory::factory()->create([
            'category_name' => 'Portfolios',
            'slug' => 'portfolios',
            'is_active' => 1,
        ]);

        HelpArticle::factory()->create([
            'help_article_category_id' => $category->id,
            'title' => 'Creating Your First Portfolio',
            'slug' => 'creating-your-first-portfolio',
            'content' => 'Portfolio article content.',
            'is_published' => 1,
        ]);

        $response = $this->getJson(
            '/api/help-article/creating-your-first-portfolio'
        );

        $response->assertOk()
            ->assertJsonPath('article.category_name', 'Portfolios')
            ->assertJsonPath('article.category_slug', 'portfolios');
    }
}
