<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $categories = [
            [
                'name' => "Women's",
                'slug' => 'women',
                'description' => "Women's collection.",
                'sort_order' => 1,
            ],
            [
                'name' => "Men's",
                'slug' => 'men',
                'description' => "Men's collection.",
                'sort_order' => 2,
            ],
        ];

        $canonicalIds = [];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['slug' => $category['slug']],
                [
                    'parent_id' => null,
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'meta_title' => $category['name'] . ' Collection | The Trend Theory',
                    'meta_description' => 'Shop ' . strtolower($category['name']) . ' fashion at The Trend Theory.',
                    'sort_order' => $category['sort_order'],
                    'is_active' => 1,
                    'show_in_nav' => 1,
                    'show_in_home' => 1,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );

            $canonicalIds[$category['slug']] = (int) DB::table('categories')
                ->where('slug', $category['slug'])
                ->value('id');
        }

        $this->moveProductsToCanonicalCategory(
            ['women', 'womens', 'womes', 'women-category', 'womens-category'],
            ["women", "women's", 'womens', 'womes', 'women category', "women's collection"],
            $canonicalIds['women']
        );

        $this->moveProductsToCanonicalCategory(
            ['men', 'mens', 'men-category', 'mens-category'],
            ['men', "men's", 'mens', 'men category', "men's collection"],
            $canonicalIds['men']
        );

        DB::table('categories')
            ->whereNotIn('slug', ['women', 'men'])
            ->update([
                'is_active' => 0,
                'show_in_nav' => 0,
                'show_in_home' => 0,
                'updated_at' => $now,
            ]);
    }

    private function moveProductsToCanonicalCategory(array $slugs, array $names, int $canonicalId): void
    {
        $oldCategoryIds = DB::table('categories')
            ->where('id', '!=', $canonicalId)
            ->where(function ($query) use ($slugs, $names) {
                $query->whereIn('slug', $slugs)
                    ->orWhereIn(DB::raw('LOWER(name)'), $names);
            })
            ->pluck('id');

        if ($oldCategoryIds->isEmpty()) {
            return;
        }

        DB::table('products')
            ->whereIn('category_id', $oldCategoryIds)
            ->update(['category_id' => $canonicalId]);
    }
}
