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
        $this->call(CategorySeeder::class);

        $categoryIds = DB::table('categories')->whereIn('slug', ['women', 'men'])->pluck('id', 'slug');
        $products = [
            ['category' => 'women', 'name' => 'Sage Bloom Wrap Dress', 'sku' => 'TTT-W-001', 'price' => 1699, 'mrp' => 2499, 'stock' => 24, 'fabric' => 'Rayon blend', 'fit' => 'Relaxed'],
            ['category' => 'women', 'name' => 'Dusty Pink Relaxed Co-ord Set', 'sku' => 'TTT-W-002', 'price' => 1999, 'mrp' => 2799, 'stock' => 18, 'fabric' => 'Cotton fleece', 'fit' => 'Relaxed'],
            ['category' => 'women', 'name' => 'Classic White Ribbed Tank Top', 'sku' => 'TTT-W-003', 'price' => 599, 'mrp' => 899, 'stock' => 50, 'fabric' => 'Rib cotton', 'fit' => 'Slim'],
            ['category' => 'women', 'name' => 'Noir Street Cargo Pants', 'sku' => 'TTT-W-004', 'price' => 1899, 'mrp' => 2599, 'stock' => 27, 'fabric' => 'Cotton twill', 'fit' => 'Straight'],
            ['category' => 'women', 'name' => 'Mauve Oversized Graphic Tee', 'sku' => 'TTT-W-005', 'price' => 899, 'mrp' => 1299, 'stock' => 44, 'fabric' => 'Premium cotton', 'fit' => 'Oversized'],
            ['category' => 'women', 'name' => 'Ivory Wide Leg Trousers', 'sku' => 'TTT-W-006', 'price' => 1599, 'mrp' => 2299, 'stock' => 21, 'fabric' => 'Cotton linen', 'fit' => 'Wide leg'],
            ['category' => 'women', 'name' => 'Olive Everyday Shirt Dress', 'sku' => 'TTT-W-007', 'price' => 1799, 'mrp' => 2499, 'stock' => 16, 'fabric' => 'Soft cotton', 'fit' => 'Regular'],
            ['category' => 'women', 'name' => 'Charcoal Crop Hoodie', 'sku' => 'TTT-W-008', 'price' => 1499, 'mrp' => 2199, 'stock' => 32, 'fabric' => 'Loop knit fleece', 'fit' => 'Cropped'],
            ['category' => 'women', 'name' => 'Skyline Pleated Skirt', 'sku' => 'TTT-W-009', 'price' => 1299, 'mrp' => 1899, 'stock' => 25, 'fabric' => 'Poly viscose', 'fit' => 'A-line'],
            ['category' => 'women', 'name' => 'Stone Wash Denim Jacket', 'sku' => 'TTT-W-010', 'price' => 2299, 'mrp' => 3199, 'stock' => 14, 'fabric' => 'Washed denim', 'fit' => 'Boxy'],

            ['category' => 'men', 'name' => 'Urban Edge Oversized T-Shirt', 'sku' => 'TTT-M-001', 'price' => 799, 'mrp' => 1199, 'stock' => 42, 'fabric' => 'Premium cotton', 'fit' => 'Oversized'],
            ['category' => 'men', 'name' => 'Midnight Navy Cuban Collar Shirt', 'sku' => 'TTT-M-002', 'price' => 1299, 'mrp' => 1799, 'stock' => 28, 'fabric' => 'Viscose cotton', 'fit' => 'Regular'],
            ['category' => 'men', 'name' => 'Everyday Flex Slim Chinos', 'sku' => 'TTT-M-003', 'price' => 1499, 'mrp' => 2199, 'stock' => 35, 'fabric' => 'Stretch cotton', 'fit' => 'Slim'],
            ['category' => 'men', 'name' => 'Slate Motion Track Pants', 'sku' => 'TTT-M-004', 'price' => 1599, 'mrp' => 2299, 'stock' => 30, 'fabric' => 'Cotton fleece', 'fit' => 'Tapered'],
            ['category' => 'men', 'name' => 'Graphite Utility Cargo Pants', 'sku' => 'TTT-M-005', 'price' => 1999, 'mrp' => 2799, 'stock' => 22, 'fabric' => 'Cotton twill', 'fit' => 'Relaxed'],
            ['category' => 'men', 'name' => 'Sand Dune Linen Blend Shirt', 'sku' => 'TTT-M-006', 'price' => 1399, 'mrp' => 1999, 'stock' => 26, 'fabric' => 'Linen cotton', 'fit' => 'Regular'],
            ['category' => 'men', 'name' => 'Blackout Heavyweight Hoodie', 'sku' => 'TTT-M-007', 'price' => 1899, 'mrp' => 2699, 'stock' => 20, 'fabric' => 'Heavy fleece', 'fit' => 'Oversized'],
            ['category' => 'men', 'name' => 'Monochrome Panel Sweatshirt', 'sku' => 'TTT-M-008', 'price' => 1599, 'mrp' => 2299, 'stock' => 31, 'fabric' => 'French terry', 'fit' => 'Regular'],
            ['category' => 'men', 'name' => 'Washed Blue Straight Denim', 'sku' => 'TTT-M-009', 'price' => 2199, 'mrp' => 2999, 'stock' => 19, 'fabric' => 'Denim cotton', 'fit' => 'Straight'],
            ['category' => 'men', 'name' => 'White Core Crew Neck Tee', 'sku' => 'TTT-M-010', 'price' => 699, 'mrp' => 999, 'stock' => 55, 'fabric' => 'Supima cotton', 'fit' => 'Regular'],
        ];

        foreach ($products as $product) {
            $existingProductId = DB::table('products')->where('sku', $product['sku'])->value('id');
            $slug = $existingProductId
                ? $this->uniqueSlug($product['name'], (int) $existingProductId)
                : $this->uniqueSlug($product['name']);

            DB::table('products')->updateOrInsert(
                ['sku' => $product['sku']],
                [
                    'category_id' => $categoryIds[$product['category']] ?? null,
                    'name' => $product['name'],
                    'slug' => $slug,
                    'short_description' => 'Premium everyday fashion with a clean, modern fit.',
                    'description' => '<h2>Product Details</h2><p>A comfortable, polished piece designed for regular wear and easy styling.</p><ul><li>Soft hand-feel fabric</li><li>Made for everyday movement</li><li>Pairs easily with streetwear basics</li></ul>',
                    'price' => $product['price'],
                    'original_price' => $product['mrp'],
                    'cost_price' => round($product['price'] * 0.55, 2),
                    'stock' => $product['stock'],
                    'low_stock_alert' => 5,
                    'fabric' => $product['fabric'],
                    'fit' => $product['fit'],
                    'care_instructions' => 'Machine wash cold. Dry in shade.',
                    'weight_grams' => 350,
                    'has_variants' => 0,
                    'meta_title' => $product['name'] . ' | The Trend Theory',
                    'meta_description' => 'Buy ' . $product['name'] . ' online at The Trend Theory.',
                    'meta_keywords' => 'fashion, ' . strtolower($product['name']),
                    'is_active' => 1,
                    'is_featured' => 0,
                    'is_new' => 1,
                    'is_trending' => 0,
                    'is_on_sale' => 1,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }

    private function uniqueSlug(string $name, ?int $ignoreProductId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 2;

        while (
            DB::table('products')
                ->where('slug', $slug)
                ->when($ignoreProductId, fn($query) => $query->where('id', '!=', $ignoreProductId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter++;
        }

        return $slug;
    }
}
