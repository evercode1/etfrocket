<?php

namespace Database\Seeders;

use App\Models\HelpArticleCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HelpArticleCategorySeeder extends Seeder
{
    public function run(): void
    {
        if (! env('ALLOW_SEEDS')) {
            return;
        }

        HelpArticleCategory::truncate();

        $categories = [
            [
                'category_name' => 'Getting Started',
                'sort_order' => 1,
            ],
            [
                'category_name' => 'Accounts',
                'sort_order' => 2,
            ],
            [
                'category_name' => 'Portfolios',
                'sort_order' => 3,
            ],
            [
                'category_name' => 'ETF Data',
                'sort_order' => 4,
            ],
            [
                'category_name' => 'Dividends',
                'sort_order' => 5,
            ],
            [
                'category_name' => 'Imports',
                'sort_order' => 6,
            ],
            [
                'category_name' => 'Support',
                'sort_order' => 7,
            ],
            [
                'category_name' => 'Billing',
                'sort_order' => 8,
            ],
            [
                'category_name' => 'Troubleshooting',
                'sort_order' => 9,
            ],
        ];

        foreach ($categories as $category) {
            HelpArticleCategory::updateOrCreate(
                [
                    'slug' => Str::slug($category['category_name']),
                ],
                [
                    'category_name' => $category['category_name'],
                    'sort_order' => $category['sort_order'],
                    'is_active' => 1,
                ]
            );
        }
    }
}
