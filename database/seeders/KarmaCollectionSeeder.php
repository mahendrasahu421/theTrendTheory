<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KarmaCollectionSeeder extends Seeder
{
    public function run(): void
    {
        $collection = Category::updateOrCreate(
            ['slug' => 'karma'],
            [
                'name' => 'KARMA',
                'description' => 'A dummy streetwear collection for KARMA oversized t-shirt drops.',
                'meta_title' => 'KARMA Collection - Vayu',
                'meta_description' => 'Shop dummy KARMA oversized t-shirt products and color variants.',
                'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=1200&auto=format&fit=crop&q=80',
                'banner_image' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=1600&auto=format&fit=crop&q=80',
                'sort_order' => 15,
                'is_active' => true,
                'show_in_nav' => true,
                'show_in_home' => true,
            ]
        );

        $size = Size::updateOrCreate(
            ['name' => 'Oversized Tshirt', 'type' => 'clothing'],
            [
                'label' => 'Oversized Tshirt',
                'value' => 'Oversized Tshirt',
                'chest' => '38 inches',
                'waist' => '32 inches',
                'length' => null,
                'is_active' => true,
                'sort_order' => 10,
            ]
        );

        $colors = [
            'Offwhite' => ['hex' => '#F8F4E8', 'code' => 'OW'],
            'Black' => ['hex' => '#111111', 'code' => 'BLK'],
            'Brown' => ['hex' => '#7A4A2E', 'code' => 'BRN'],
            'Lavender' => ['hex' => '#BBA7D9', 'code' => 'LAV'],
            'Sage Green' => ['hex' => '#9CAF88', 'code' => 'SGR'],
        ];

        $colorModels = [];
        foreach ($colors as $name => $meta) {
            $colorModels[$name] = Color::updateOrCreate(
                ['name' => $name],
                [
                    'label' => $name,
                    'value' => $name,
                    'type' => 'solid',
                    'hex' => $meta['hex'],
                    'is_active' => true,
                    'sort_order' => array_search($name, array_keys($colors), true) * 10 + 10,
                ]
            );
        }

        $products = [
            [
                'sku' => 'KARMA-OTS-001',
                'name' => 'KARMA Oversized Graphic T-Shirt',
                'image' => 'https://images.unsplash.com/photo-1562157873-818bc0726f68?w=900&auto=format&fit=crop&q=80',
                'price' => 699.00,
                'original_price' => 1299.00,
                'cost_price' => 320.00,
                'stock_per_color' => 20,
            ],
            [
                'sku' => 'KARMA-OTS-002',
                'name' => 'KARMA Back Print Heavyweight Tee',
                'image' => 'https://images.unsplash.com/photo-1618354691438-25bc04584c03?w=900&auto=format&fit=crop&q=80',
                'price' => 749.00,
                'original_price' => 1399.00,
                'cost_price' => 350.00,
                'stock_per_color' => 18,
            ],
            [
                'sku' => 'KARMA-OTS-003',
                'name' => 'KARMA Minimal Logo Drop Shoulder Tee',
                'image' => 'https://images.unsplash.com/photo-1618354691551-44de113f0164?w=900&auto=format&fit=crop&q=80',
                'price' => 649.00,
                'original_price' => 1199.00,
                'cost_price' => 300.00,
                'stock_per_color' => 22,
            ],
        ];

        foreach ($products as $index => $item) {
            $product = Product::updateOrCreate(
                ['sku' => $item['sku']],
                [
                    'category_id' => $collection->id,
                    'name' => $item['name'],
                    'slug' => Str::slug($item['name'] . '-' . $item['sku']),
                    'short_description' => 'KARMA dummy collection oversized t-shirt with premium streetwear fit.',
                    'description' => '<p>KARMA dummy product created for testing collection, product variants and SKU flow.</p>',
                    'price' => $item['price'],
                    'original_price' => $item['original_price'],
                    'cost_price' => $item['cost_price'],
                    'image' => $item['image'],
                    'front_image' => $item['image'],
                    'back_image' => $item['image'],
                    'available_print_sides' => 'both',
                    'stock' => $item['stock_per_color'] * count($colors),
                    'has_variants' => true,
                    'low_stock_alert' => 10,
                    'total_sold' => 0,
                    'fabric' => '240 GSM super combed cotton',
                    'fit' => 'Oversized drop shoulder fit',
                    'care_instructions' => 'Machine wash cold inside-out. Do not bleach. Iron on reverse.',
                    'weight' => 0.30,
                    'weight_grams' => 300,
                    'meta_title' => $item['name'] . ' - KARMA Collection',
                    'meta_description' => 'Dummy KARMA oversized t-shirt product for collection testing.',
                    'meta_keywords' => 'karma, oversized tshirt, streetwear, dummy product',
                    'og_image' => $item['image'],
                    'is_active' => true,
                    'is_featured' => $index === 0,
                    'is_new' => true,
                    'is_trending' => true,
                    'is_on_sale' => true,
                ]
            );

            foreach ($colors as $colorName => $meta) {
                ProductVariant::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'size' => $size->name,
                        'color' => $colorName,
                    ],
                    [
                        'size_id' => $size->id,
                        'color_id' => $colorModels[$colorName]->id,
                        'color_hex' => $meta['hex'],
                        'sku' => $item['sku'] . '-' . $meta['code'] . '-OS',
                        'price' => $item['price'],
                        'original_price' => $item['original_price'],
                        'cost_price' => $item['cost_price'],
                        'stock' => $item['stock_per_color'],
                        'sort_order' => array_search($colorName, array_keys($colors), true),
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
