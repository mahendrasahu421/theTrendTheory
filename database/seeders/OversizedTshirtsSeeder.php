<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\ProductVariant;
use App\Models\ProductImage;

class OversizedTshirtsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure Standard Sizes Exist
        $sizesList = ['S', 'M', 'L', 'XL', 'XXL'];
        $sizeIds = [];
        foreach ($sizesList as $sName) {
            $size = Size::firstOrCreate(
                ['name' => $sName],
                ['sort_order' => count($sizeIds) + 1, 'is_active' => true]
            );
            $sizeIds[$sName] = $size->id;
        }

        // 2. Ensure Rich Streetwear Colors Exist
        $colorsMaster = [
            'Vintage Charcoal Black' => ['hex' => '#18181b', 'type' => 'solid'],
            'Off-White Cloud'        => ['hex' => '#f4f4f5', 'type' => 'solid'],
            'Forest Sage Green'      => ['hex' => '#4a5d4e', 'type' => 'solid'],
            'Sunset Rust'            => ['hex' => '#a04328', 'type' => 'solid'],
            'Cobalt Electric Blue'   => ['hex' => '#1d4ed8', 'type' => 'solid'],
            'Mocha Earth Brown'      => ['hex' => '#543d2b', 'type' => 'solid'],
            'Dusty Lavender'         => ['hex' => '#9d8ea7', 'type' => 'solid'],
            'Acid Washed Grey'       => ['hex' => '#475569', 'type' => 'solid'],
            'Olive Drab'             => ['hex' => '#556b2f', 'type' => 'solid'],
            'Crimson Wine'           => ['hex' => '#7f1d1d', 'type' => 'solid'],
        ];

        $colorIds = [];
        foreach ($colorsMaster as $cName => $cInfo) {
            $color = Color::firstOrCreate(
                ['name' => $cName],
                ['hex' => $cInfo['hex'], 'label' => $cName, 'value' => $cName, 'type' => $cInfo['type'], 'is_active' => true]
            );
            $colorIds[$cName] = $color->id;
        }

        // 3. Ensure Categories Exist
        $catWomen = Category::firstOrCreate(
            ['slug' => 'women'],
            [
                'name' => "Women's Oversized",
                'description' => 'Aesthetic oversized graphic tees, drop-shoulder streetwear silhouettes for women.',
                'image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'show_in_nav' => true,
                'show_in_home' => true,
                'sort_order' => 1
            ]
        );

        $catMen = Category::firstOrCreate(
            ['slug' => 'men'],
            [
                'name' => "Men's Oversized",
                'description' => 'Heavyweight 240 GSM drop-shoulder boxy oversized tees for men.',
                'image' => 'https://images.unsplash.com/photo-1506630448388-4e683c67ddb0?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'show_in_nav' => true,
                'show_in_home' => true,
                'sort_order' => 2
            ]
        );

        $catOversized = Category::firstOrCreate(
            ['slug' => 'oversized-t-shirts'],
            [
                'name' => "Oversized T-Shirts",
                'description' => 'Signature luxury heavy cotton oversized tees curated for the modern youth.',
                'image' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'show_in_nav' => true,
                'show_in_home' => true,
                'sort_order' => 3
            ]
        );

        // 4. Curated Oversized Graphic & Minimalist Tee Designs with Multiple Colorways and Multi-Angle Photo Shoots
        $teeCollections = [
            [
                'title' => 'Sunlit Butterfly Aesthetic Oversized Tee',
                'base_sku' => 'TTT-BTRFLY',
                'category_id' => $catWomen->id,
                'price' => 499.00,
                'mrp' => 999.00,
                'fabric' => '240 GSM 100% Super Combed Cotton',
                'fit' => 'Relaxed Drop Shoulder Oversized Fit',
                'description' => 'Embrace effortless style with our premium oversized butterfly graphic tee. Designed for comfort and individuality, this piece features a large vintage-inspired butterfly artwork symbolizing freedom, transformation, and confidence. Crafted from soft, heavyweight 240 GSM cotton with a relaxed oversized silhouette.',
                'colors' => [
                    [
                        'name' => 'Forest Sage Green',
                        'hex' => '#4a5d4e',
                        'images' => [
                            'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1503342394128-c104d54dba01?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=900&auto=format&fit=crop&q=80',
                        ]
                    ],
                    [
                        'name' => 'Vintage Charcoal Black',
                        'hex' => '#18181b',
                        'images' => [
                            'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1527719327859-c6ce80353573?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1562157873-818bc0726f68?w=900&auto=format&fit=crop&q=80',
                        ]
                    ],
                    [
                        'name' => 'Off-White Cloud',
                        'hex' => '#f4f4f5',
                        'images' => [
                            'https://images.unsplash.com/photo-1618354691438-25bc04584c03?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1578632767115-351597cf2477?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=900&auto=format&fit=crop&q=80',
                        ]
                    ],
                ]
            ],
            [
                'title' => 'Tokyo Cyber Matrix Neo-Kanji Oversized Tee',
                'base_sku' => 'TTT-TOKYO',
                'category_id' => $catMen->id,
                'price' => 499.00,
                'mrp' => 1299.00,
                'fabric' => '250 GSM French Terry Bio-Washed Cotton',
                'fit' => 'Boxy Drop Shoulder Oversized Fit',
                'description' => 'Cyberpunk aesthetics meet Shibuya street culture. High-density puff print detailing on front and full back matrix typography. Made from heavy breathable cotton designed to drape perfectly.',
                'colors' => [
                    [
                        'name' => 'Vintage Charcoal Black',
                        'hex' => '#18181b',
                        'images' => [
                            'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1503342394128-c104d54dba01?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=900&auto=format&fit=crop&q=80',
                        ]
                    ],
                    [
                        'name' => 'Cobalt Electric Blue',
                        'hex' => '#1d4ed8',
                        'images' => [
                            'https://images.unsplash.com/photo-1527719327859-c6ce80353573?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1562157873-818bc0726f68?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=900&auto=format&fit=crop&q=80',
                        ]
                    ],
                    [
                        'name' => 'Acid Washed Grey',
                        'hex' => '#475569',
                        'images' => [
                            'https://images.unsplash.com/photo-1578632767115-351597cf2477?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1618354691438-25bc04584c03?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=900&auto=format&fit=crop&q=80',
                        ]
                    ],
                ]
            ],
            [
                'title' => 'Renaissance Angel Sculpture Oversized Street Tee',
                'base_sku' => 'TTT-ANGEL',
                'category_id' => $catOversized->id,
                'price' => 549.00,
                'mrp' => 1199.00,
                'fabric' => '240 GSM 100% Organic Heavy Cotton',
                'fit' => 'Signature Oversized Street Cut',
                'description' => 'Classic renaissance marble artwork blended with modern high-street luxury. Pre-shrunk, soft-washed fabric with ribbed round crew collar.',
                'colors' => [
                    [
                        'name' => 'Off-White Cloud',
                        'hex' => '#f4f4f5',
                        'images' => [
                            'https://images.unsplash.com/photo-1618354691551-44de113f0164?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1618354691438-25bc04584c03?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1578632767115-351597cf2477?w=900&auto=format&fit=crop&q=80',
                        ]
                    ],
                    [
                        'name' => 'Mocha Earth Brown',
                        'hex' => '#543d2b',
                        'images' => [
                            'https://images.unsplash.com/photo-1503342394128-c104d54dba01?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=900&auto=format&fit=crop&q=80',
                        ]
                    ],
                    [
                        'name' => 'Vintage Charcoal Black',
                        'hex' => '#18181b',
                        'images' => [
                            'https://images.unsplash.com/photo-1562157873-818bc0726f68?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1527719327859-c6ce80353573?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=900&auto=format&fit=crop&q=80',
                        ]
                    ],
                ]
            ],
            [
                'title' => 'Eternal Chaos Abstract Heavyweight Streetwear Tee',
                'base_sku' => 'TTT-CHAOS',
                'category_id' => $catMen->id,
                'price' => 499.00,
                'mrp' => 1099.00,
                'fabric' => '240 GSM Compact Combed Cotton',
                'fit' => 'Drop-Shoulder Boxy Loose Silhouette',
                'description' => 'Abstract cosmic geometry and dystopian streetwear motifs. Breathable, sweat-resistant luxury cotton designed for day-to-night styling.',
                'colors' => [
                    [
                        'name' => 'Sunset Rust',
                        'hex' => '#a04328',
                        'images' => [
                            'https://images.unsplash.com/photo-1503342394128-c104d54dba01?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1578632767115-351597cf2477?w=900&auto=format&fit=crop&q=80',
                        ]
                    ],
                    [
                        'name' => 'Vintage Charcoal Black',
                        'hex' => '#18181b',
                        'images' => [
                            'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1527719327859-c6ce80353573?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1562157873-818bc0726f68?w=900&auto=format&fit=crop&q=80',
                        ]
                    ],
                    [
                        'name' => 'Forest Sage Green',
                        'hex' => '#4a5d4e',
                        'images' => [
                            'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1618354691438-25bc04584c03?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1618354691551-44de113f0164?w=900&auto=format&fit=crop&q=80',
                        ]
                    ],
                ]
            ],
            [
                'title' => 'Velvet Blossom Botanical Graphic Oversized Tee',
                'base_sku' => 'TTT-BLSSM',
                'category_id' => $catWomen->id,
                'price' => 499.00,
                'mrp' => 999.00,
                'fabric' => '240 GSM Premium Bio-Washed Cotton',
                'fit' => 'Drop Shoulder Relaxed Oversized Fit',
                'description' => 'Botanical aesthetics with soft floral illustrations on heavyweight oversized tee. Effortlessly pairs with baggy denim, cargos, or bike shorts.',
                'colors' => [
                    [
                        'name' => 'Dusty Lavender',
                        'hex' => '#9d8ea7',
                        'images' => [
                            'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1618354691438-25bc04584c03?w=900&auto=format&fit=crop&q=80',
                        ]
                    ],
                    [
                        'name' => 'Off-White Cloud',
                        'hex' => '#f4f4f5',
                        'images' => [
                            'https://images.unsplash.com/photo-1618354691551-44de113f0164?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1578632767115-351597cf2477?w=900&auto=format&fit=crop&q=80',
                        ]
                    ],
                    [
                        'name' => 'Vintage Charcoal Black',
                        'hex' => '#18181b',
                        'images' => [
                            'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1527719327859-c6ce80353573?w=900&auto=format&fit=crop&q=80',
                        ]
                    ],
                ]
            ],
            [
                'title' => 'Vayu Signature Minimal Boxy Tee',
                'base_sku' => 'TTT-SIGNTR',
                'category_id' => $catOversized->id,
                'price' => 599.00,
                'mrp' => 1299.00,
                'fabric' => '260 GSM Heavyweight Terry Cotton',
                'fit' => 'Signature Boxy Drop Shoulder',
                'description' => 'The flagship minimalist essential. Clean silicone rubber embossed chest branding with luxury drop shoulder drape that retains structure wash after wash.',
                'colors' => [
                    [
                        'name' => 'Vintage Charcoal Black',
                        'hex' => '#18181b',
                        'images' => [
                            'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1503342394128-c104d54dba01?w=900&auto=format&fit=crop&q=80',
                        ]
                    ],
                    [
                        'name' => 'Forest Sage Green',
                        'hex' => '#4a5d4e',
                        'images' => [
                            'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1618354691438-25bc04584c03?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1578632767115-351597cf2477?w=900&auto=format&fit=crop&q=80',
                        ]
                    ],
                    [
                        'name' => 'Off-White Cloud',
                        'hex' => '#f4f4f5',
                        'images' => [
                            'https://images.unsplash.com/photo-1618354691551-44de113f0164?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1562157873-818bc0726f68?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1527719327859-c6ce80353573?w=900&auto=format&fit=crop&q=80',
                        ]
                    ],
                ]
            ],
            [
                'title' => 'Cyber Panther Midnight Edition Oversized Tee',
                'base_sku' => 'TTT-PANTHR',
                'category_id' => $catMen->id,
                'price' => 499.00,
                'mrp' => 1199.00,
                'fabric' => '240 GSM French Terry Cotton',
                'fit' => 'Drop Shoulder Loose Street Fit',
                'description' => 'Fierce neo-trad panther artwork with luminous metallic chrome ink highlights. Engineered with pre-shrunk cotton for zero shrinkage.',
                'colors' => [
                    [
                        'name' => 'Vintage Charcoal Black',
                        'hex' => '#18181b',
                        'images' => [
                            'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1503342394128-c104d54dba01?w=900&auto=format&fit=crop&q=80',
                        ]
                    ],
                    [
                        'name' => 'Crimson Wine',
                        'hex' => '#7f1d1d',
                        'images' => [
                            'https://images.unsplash.com/photo-1527719327859-c6ce80353573?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1562157873-818bc0726f68?w=900&auto=format&fit=crop&q=80',
                        ]
                    ],
                    [
                        'name' => 'Acid Washed Grey',
                        'hex' => '#475569',
                        'images' => [
                            'https://images.unsplash.com/photo-1578632767115-351597cf2477?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1618354691438-25bc04584c03?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=900&auto=format&fit=crop&q=80',
                        ]
                    ],
                ]
            ],
            [
                'title' => 'Lost In Nirvana Acid-Wash Heavy Street Tee',
                'base_sku' => 'TTT-NRVNA',
                'category_id' => $catOversized->id,
                'price' => 549.00,
                'mrp' => 1299.00,
                'fabric' => '250 GSM Washed Vintage Heavy Cotton',
                'fit' => 'Oversized Boxy Silhouette',
                'description' => '90s grunge rock nostalgia reimagined for the modern luxury streetwear scene. Authentic mineral wash pattern on each individual piece.',
                'colors' => [
                    [
                        'name' => 'Acid Washed Grey',
                        'hex' => '#475569',
                        'images' => [
                            'https://images.unsplash.com/photo-1578632767115-351597cf2477?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1503342394128-c104d54dba01?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=900&auto=format&fit=crop&q=80',
                        ]
                    ],
                    [
                        'name' => 'Vintage Charcoal Black',
                        'hex' => '#18181b',
                        'images' => [
                            'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1527719327859-c6ce80353573?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1562157873-818bc0726f68?w=900&auto=format&fit=crop&q=80',
                        ]
                    ],
                    [
                        'name' => 'Olive Drab',
                        'hex' => '#556b2f',
                        'images' => [
                            'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1618354691438-25bc04584c03?w=900&auto=format&fit=crop&q=80',
                            'https://images.unsplash.com/photo-1618354691551-44de113f0164?w=900&auto=format&fit=crop&q=80',
                        ]
                    ],
                ]
            ],
        ];

        // 5. Insert each Oversized Design, its Color Variants, Gallery Images & Size Variants
        foreach ($teeCollections as $cIdx => $item) {
            $parentProduct = null;

            foreach ($item['colors'] as $colorIdx => $colorOption) {
                $cName = $colorOption['name'];
                $cHex = $colorOption['hex'];
                $images = $colorOption['images'];
                $targetColorId = $colorIds[$cName] ?? null;

                $colorSuffix = Str::slug($cName);
                $sku = $item['base_sku'] . '-' . strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $cName), 0, 4));
                $slug = Str::slug($item['title'] . '-' . $cName);

                $isParent = ($colorIdx === 0);

                $product = Product::updateOrCreate(
                    ['sku' => $sku],
                    [
                        'name' => $item['title'],
                        'slug' => $slug,
                        'category_id' => $item['category_id'],
                        'parent_product_id' => $isParent ? null : $parentProduct->id,
                        'product_type' => $isParent ? 'color_variant_parent' : 'color_variant',
                        'color_name' => $cName,
                        'color_hex' => $cHex,
                        'price' => $item['price'],
                        'original_price' => $item['mrp'],
                        'cost_price' => 220.00,
                        'stock' => 150,
                        'image' => $images[0],
                        'short_description' => $item['description'],
                        'description' => '<h3>Vayu • LUXURY OVERSIZED</h3>' .
                            '<p>' . $item['description'] . '</p>' .
                            '<ul>' .
                            '<li><strong>Fabric:</strong> ' . $item['fabric'] . '</li>' .
                            '<li><strong>Fit:</strong> ' . $item['fit'] . '</li>' .
                            '<li><strong>Color:</strong> ' . $cName . '</li>' .
                            '<li><strong>GSM:</strong> 240+ Heavyweight French Terry</li>' .
                            '<li><strong>Care:</strong> Machine wash cold inside-out, tumble dry low, do not iron on print</li>' .
                            '<li><strong>Shipping:</strong> Ships within 24 hours with Express Tracking</li>' .
                            '</ul>',
                        'fabric' => $item['fabric'],
                        'fit' => $item['fit'],
                        'care_instructions' => 'Machine wash cold with like colors inside out. Tumble dry low or line dry in shade. Do not iron directly on graphics.',
                        'weight_grams' => 240,
                        'is_active' => true,
                        'is_featured' => true,
                        'is_trending' => true,
                        'is_new' => true,
                        'is_on_sale' => true,
                        'total_sold' => rand(150, 850)
                    ]
                );

                if ($isParent) {
                    $parentProduct = $product;
                }

                // Clean & Seed Multiple ProductImages
                ProductImage::where('product_id', $product->id)->delete();
                foreach ($images as $imgIdx => $imgUrl) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'color_id' => $targetColorId,
                        'url' => $imgUrl,
                        'alt_text' => $item['title'] . ' in ' . $cName . ' - Angle ' . ($imgIdx + 1),
                        'is_primary' => ($imgIdx === 0),
                        'sort_order' => $imgIdx + 1
                    ]);
                }

                // Seed Sizes Variants for this Colorway
                foreach ($sizesList as $sIdx => $sName) {
                    ProductVariant::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'size' => $sName,
                            'color' => $cName
                        ],
                        [
                            'size_id' => $sizeIds[$sName] ?? null,
                            'color_id' => $targetColorId,
                            'color_hex' => $cHex,
                            'sku' => $sku . '-' . $sName,
                            'price' => $item['price'],
                            'original_price' => $item['mrp'],
                            'cost_price' => 220.00,
                            'stock' => 30,
                            'sort_order' => $sIdx + 1,
                            'is_active' => true
                        ]
                    );
                }
            }
        }
    }
}
