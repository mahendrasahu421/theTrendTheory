<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DemoProductSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $categories = [
            ['name' => 'Men', 'slug' => 'men', 'sort_order' => 10],
            ['name' => 'Women', 'slug' => 'women', 'sort_order' => 20],
            ['name' => 'New Arrivals', 'slug' => 'new-arrivals', 'sort_order' => 30],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'description' => 'Curated fashion picks for ' . strtolower($category['name']) . '.',
                    'sort_order' => $category['sort_order'],
                    'is_active' => 1,
                    'show_in_nav' => 1,
                    'show_in_home' => 1,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }

        $categoryIds = DB::table('categories')->whereIn('slug', ['men', 'women', 'new-arrivals'])->pluck('id', 'slug');
        $products = [
            ['category' => 'men', 'name' => 'Urban Edge Oversized T-Shirt', 'sku' => 'TTT-M-001', 'price' => 799, 'mrp' => 1199, 'stock' => 42, 'featured' => 1, 'new' => 1],
            ['category' => 'men', 'name' => 'Midnight Navy Cuban Collar Shirt', 'sku' => 'TTT-M-002', 'price' => 1299, 'mrp' => 1799, 'stock' => 28, 'featured' => 1, 'new' => 0],
            ['category' => 'men', 'name' => 'Everyday Flex Slim Chinos', 'sku' => 'TTT-M-003', 'price' => 1499, 'mrp' => 2199, 'stock' => 35, 'featured' => 0, 'new' => 1],
            ['category' => 'women', 'name' => 'Sage Bloom Wrap Dress', 'sku' => 'TTT-W-001', 'price' => 1699, 'mrp' => 2499, 'stock' => 24, 'featured' => 1, 'new' => 1],
            ['category' => 'women', 'name' => 'Dusty Pink Relaxed Co-ord Set', 'sku' => 'TTT-W-002', 'price' => 1999, 'mrp' => 2799, 'stock' => 18, 'featured' => 1, 'new' => 1],
            ['category' => 'women', 'name' => 'Classic White Ribbed Tank Top', 'sku' => 'TTT-W-003', 'price' => 599, 'mrp' => 899, 'stock' => 50, 'featured' => 0, 'new' => 0],
            ['category' => 'new-arrivals', 'name' => 'Charcoal Street Utility Jacket', 'sku' => 'TTT-N-001', 'price' => 2499, 'mrp' => 3499, 'stock' => 16, 'featured' => 1, 'new' => 1],
            ['category' => 'new-arrivals', 'name' => 'Burgundy Weekend Hoodie', 'sku' => 'TTT-N-002', 'price' => 1799, 'mrp' => 2599, 'stock' => 30, 'featured' => 0, 'new' => 1],
        ];

        foreach ($products as $product) {
            $slug = Str::slug($product['name']);

            DB::table('products')->updateOrInsert(
                ['sku' => $product['sku']],
                [
                    'category_id' => $categoryIds[$product['category']] ?? null,
                    'name' => $product['name'],
                    'slug' => $slug,
                    'short_description' => 'Premium everyday fashion with a clean, modern fit.',
                    'description' => 'A comfortable, polished piece designed for regular wear and easy styling.',
                    'price' => $product['price'],
                    'original_price' => $product['mrp'],
                    'cost_price' => round($product['price'] * 0.55, 2),
                    'stock' => $product['stock'],
                    'low_stock_alert' => 5,
                    'fabric' => 'Cotton blend',
                    'fit' => 'Regular',
                    'care_instructions' => 'Machine wash cold. Dry in shade.',
                    'weight_grams' => 350,
                    'has_variants' => 0,
                    'meta_title' => $product['name'] . ' | The Trend Theory',
                    'meta_description' => 'Buy ' . $product['name'] . ' online at The Trend Theory.',
                    'meta_keywords' => 'fashion, ' . strtolower($product['name']),
                    'is_active' => 1,
                    'is_featured' => $product['featured'],
                    'is_new' => $product['new'],
                    'is_trending' => $product['featured'],
                    'is_on_sale' => 1,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }
}
