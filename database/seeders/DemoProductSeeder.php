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
use App\Models\Review;
use App\Models\Coupon;

class DemoProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure Sizes exist
        $sizesList = ['S', 'M', 'L', 'XL', 'XXL'];
        $sizeIds = [];
        foreach ($sizesList as $sName) {
            $size = Size::firstOrCreate(['name' => $sName], ['sort_order' => count($sizeIds) + 1, 'is_active' => true]);
            $sizeIds[$sName] = $size->id;
        }

        // 2. Ensure Colors exist
        $colorsList = [
            'Black' => '#111111',
            'Off White' => '#F5F5F0',
            'Charcoal' => '#2D3748',
            'Navy Blue' => '#0F2747',
            'Olive Green' => '#4A5538',
            'Burgundy' => '#6B1D2F',
            'Sage' => '#8A9A86',
            'Dusty Pink' => '#D4A5A5',
            'Cobalt' => '#1E40AF',
            'Beige' => '#D2B48C'
        ];
        $colorIds = [];
        foreach ($colorsList as $cName => $cHex) {
            $color = Color::firstOrCreate(['name' => $cName], ['hex' => $cHex, 'is_active' => true]);
            $colorIds[$cName] = $color->id;
        }

        // 3. Ensure Categories exist
        $catMen = Category::firstOrCreate(
            ['slug' => 'men'],
            [
                'name' => "Men's Collection",
                'description' => 'Luxury streetwear, oversized tees, hoodies, and cargo essentials for men.',
                'image' => 'https://images.unsplash.com/photo-1506630448388-4e683c67ddb0?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'show_in_nav' => true,
                'show_in_home' => true,
                'sort_order' => 1
            ]
        );

        $catWomen = Category::firstOrCreate(
            ['slug' => 'women'],
            [
                'name' => "Women's Collection",
                'description' => 'Aesthetic street culture, crop hoodies, co-ords, and wide-leg cargos for women.',
                'image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'show_in_nav' => true,
                'show_in_home' => true,
                'sort_order' => 2
            ]
        );

        $catAccessories = Category::firstOrCreate(
            ['slug' => 'accessories'],
            [
                'name' => 'Accessories & Drops',
                'description' => 'Headwear, streetwear sling bags, caps, and exclusive drop pieces.',
                'image' => 'https://images.unsplash.com/photo-1576871337622-98d48d1cf531?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'show_in_nav' => true,
                'show_in_home' => true,
                'sort_order' => 3
            ]
        );

        // 4. Products Master Data (50 Curated Items, all priced at ₹499 with ₹999–₹1499 MRP)
        $productsData = [
            // Men's Oversized Tees (1-10)
            [
                'name' => 'Tokyo Matrix Cyberpunk Oversized Graphic Tee',
                'category_id' => $catMen->id,
                'mrp' => 1299,
                'image' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=800&auto=format&fit=crop&q=80',
                'fabric' => '240 GSM 100% Super Combed Cotton',
                'fit' => 'Drop Shoulder Oversized Fit',
                'color' => 'Black',
                'tag' => 'Oversized Tee'
            ],
            [
                'name' => 'Lost In Tokyo Vintage Acid Wash Graphic Tee',
                'category_id' => $catMen->id,
                'mrp' => 1199,
                'image' => 'https://images.unsplash.com/photo-1578632767115-351597cf2477?w=800&auto=format&fit=crop&q=80',
                'fabric' => '240 GSM Mineral Washed Cotton',
                'fit' => 'Boxy Streetwear Fit',
                'color' => 'Charcoal',
                'tag' => 'Graphic Tee'
            ],
            [
                'name' => 'Rebellion Gothic Typography Heavyweight Tee',
                'category_id' => $catMen->id,
                'mrp' => 1099,
                'image' => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=800&auto=format&fit=crop&q=80',
                'fabric' => '260 GSM French Terry Cotton',
                'fit' => 'Oversized Boxy Fit',
                'color' => 'Black',
                'tag' => 'Streetwear'
            ],
            [
                'name' => 'Neo Samurai Kanji Oversized Streetwear T-Shirt',
                'category_id' => $catMen->id,
                'mrp' => 1299,
                'image' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=800&auto=format&fit=crop&q=80',
                'fabric' => '240 GSM Combed Bio-Washed Cotton',
                'fit' => 'Drop Shoulder Loose Fit',
                'color' => 'Off White',
                'tag' => 'Anime Graphic'
            ],
            [
                'name' => 'Minimalist Aesthetic Wave Embroidery Boxy Tee',
                'category_id' => $catMen->id,
                'mrp' => 999,
                'image' => 'https://images.unsplash.com/photo-1618354691438-25bc04584c03?w=800&auto=format&fit=crop&q=80',
                'fabric' => '220 GSM 100% Cotton Terry',
                'fit' => 'Relaxed Fit',
                'color' => 'Sage',
                'tag' => 'Minimal Street'
            ],
            [
                'name' => 'Acid Wash Shadow Viper Graphic Oversized Tee',
                'category_id' => $catMen->id,
                'mrp' => 1199,
                'image' => 'https://images.unsplash.com/photo-1503342394128-c104d54dba01?w=800&auto=format&fit=crop&q=80',
                'fabric' => '240 GSM Washed Cotton',
                'fit' => 'Drop Shoulder Fit',
                'color' => 'Charcoal',
                'tag' => 'Acid Wash'
            ],
            [
                'name' => 'Cybernetics Blueprint Technical Heavy Tee',
                'category_id' => $catMen->id,
                'mrp' => 1299,
                'image' => 'https://images.unsplash.com/photo-1618354691551-44de113f0164?w=800&auto=format&fit=crop&q=80',
                'fabric' => '250 GSM Heavy Cotton',
                'fit' => 'Oversized Fit',
                'color' => 'Off White',
                'tag' => 'Techwear'
            ],
            [
                'name' => 'Astral Dimension Distressed Streetwear Tee',
                'category_id' => $catMen->id,
                'mrp' => 1099,
                'image' => 'https://images.unsplash.com/photo-1527719327859-c6ce80353573?w=800&auto=format&fit=crop&q=80',
                'fabric' => '230 GSM Bio-washed Cotton',
                'fit' => 'Relaxed Street Fit',
                'color' => 'Navy Blue',
                'tag' => 'Graphic Tee'
            ],
            [
                'name' => 'Underground Syndicate High-Density Puff Print Tee',
                'category_id' => $catMen->id,
                'mrp' => 1399,
                'image' => 'https://images.unsplash.com/photo-1562157873-818bc0726f68?w=800&auto=format&fit=crop&q=80',
                'fabric' => '260 GSM Heavyweight Terry',
                'fit' => 'Boxy Drop Shoulder',
                'color' => 'Black',
                'tag' => 'Puff Print'
            ],
            [
                'name' => 'Vintage Racing Club Oversized Graphic T-Shirt',
                'category_id' => $catMen->id,
                'mrp' => 1199,
                'image' => 'https://images.unsplash.com/photo-1503341455253-b2e723bb3dbb?w=800&auto=format&fit=crop&q=80',
                'fabric' => '240 GSM Pure Cotton',
                'fit' => 'Oversized Street Fit',
                'color' => 'Beige',
                'tag' => 'Vintage Racing'
            ],

            // Men's Hoodies & Sweats (11-18)
            [
                'name' => 'Obsidian Stealth 380 GSM Heavyweight Street Hoodie',
                'category_id' => $catMen->id,
                'mrp' => 1499,
                'image' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=800&auto=format&fit=crop&q=80',
                'fabric' => '380 GSM French Terry Fleece',
                'fit' => 'Oversized Boxy Hoodie',
                'color' => 'Black',
                'tag' => 'Heavyweight Hoodie'
            ],
            [
                'name' => 'Tokyo Midnight Kanji Raw Hem Street Sweatshirt',
                'category_id' => $catMen->id,
                'mrp' => 1399,
                'image' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=800&auto=format&fit=crop&q=80',
                'fabric' => '340 GSM Cotton Loopknit',
                'fit' => 'Drop Shoulder Sweatshirt',
                'color' => 'Charcoal',
                'tag' => 'Sweatshirt'
            ],
            [
                'name' => 'Astral Wave Pullover Minimalist Street Hoodie',
                'category_id' => $catMen->id,
                'mrp' => 1499,
                'image' => 'https://images.unsplash.com/photo-1620799140188-3b2a02fd9a77?w=800&auto=format&fit=crop&q=80',
                'fabric' => '360 GSM Super Combed Fleece',
                'fit' => 'Relaxed Oversized Hoodie',
                'color' => 'Off White',
                'tag' => 'Street Hoodie'
            ],
            [
                'name' => 'Cobalt Cybernetics Graphic Back Print Hoodie',
                'category_id' => $catMen->id,
                'mrp' => 1499,
                'image' => 'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?w=800&auto=format&fit=crop&q=80',
                'fabric' => '380 GSM Warm Fleece',
                'fit' => 'Boxy Fit',
                'color' => 'Cobalt',
                'tag' => 'Graphic Hoodie'
            ],
            [
                'name' => 'Phantom Acid Washed Distressed Zip Hoodie',
                'category_id' => $catMen->id,
                'mrp' => 1499,
                'image' => 'https://images.unsplash.com/photo-1620799139834-6b8f844fbe61?w=800&auto=format&fit=crop&q=80',
                'fabric' => '350 GSM Washed French Terry',
                'fit' => 'Relaxed Zip-Up Fit',
                'color' => 'Charcoal',
                'tag' => 'Zip Hoodie'
            ],
            [
                'name' => 'Sage Horizon Embroidered Minimal Crewneck',
                'category_id' => $catMen->id,
                'mrp' => 1299,
                'image' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=800&auto=format&fit=crop&q=80',
                'fabric' => '320 GSM Loopknit Cotton',
                'fit' => 'Comfort Fit Crewneck',
                'color' => 'Sage',
                'tag' => 'Crewneck'
            ],
            [
                'name' => 'Viper Strike Cyber Graphic Street Hoodie',
                'category_id' => $catMen->id,
                'mrp' => 1499,
                'image' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=800&auto=format&fit=crop&q=80',
                'fabric' => '380 GSM Premium Cotton Fleece',
                'fit' => 'Oversized Fit',
                'color' => 'Black',
                'tag' => 'Streetwear'
            ],
            [
                'name' => 'Burgundy Shadow Monogram Fleece Pullover',
                'category_id' => $catMen->id,
                'mrp' => 1399,
                'image' => 'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?w=800&auto=format&fit=crop&q=80',
                'fabric' => '340 GSM Brushed Fleece',
                'fit' => 'Relaxed Fit',
                'color' => 'Burgundy',
                'tag' => 'Sweatshirt'
            ],

            // Men's Bottoms & Cargos (19-25)
            [
                'name' => 'Tactical 8-Pocket Relaxed Streetwear Cargo Pants',
                'category_id' => $catMen->id,
                'mrp' => 1499,
                'image' => 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=800&auto=format&fit=crop&q=80',
                'fabric' => '100% Cotton Ripstop Twill',
                'fit' => 'Relaxed Straight Leg',
                'color' => 'Black',
                'tag' => 'Cargo Pants'
            ],
            [
                'name' => 'Cyberpunk Technical Strapped Jogger Cargos',
                'category_id' => $catMen->id,
                'mrp' => 1499,
                'image' => 'https://images.unsplash.com/photo-1584370848010-d7fe6bc767ec?w=800&auto=format&fit=crop&q=80',
                'fabric' => 'Water-Resistant Cotton Poly Twill',
                'fit' => 'Tapered Cuff Techwear',
                'color' => 'Black',
                'tag' => 'Techwear Cargos'
            ],
            [
                'name' => 'Vintage Washed Olive Utility Streetwear Cargos',
                'category_id' => $catMen->id,
                'mrp' => 1399,
                'image' => 'https://images.unsplash.com/photo-1542272604-780c96856592?w=800&auto=format&fit=crop&q=80',
                'fabric' => 'Enzyme Washed Cotton Twill',
                'fit' => 'Baggy Cargo Fit',
                'color' => 'Olive Green',
                'tag' => 'Utility Cargo'
            ],
            [
                'name' => 'Heavyweight Streetwear Fleece Lounge Joggers',
                'category_id' => $catMen->id,
                'mrp' => 1299,
                'image' => 'https://images.unsplash.com/photo-1584370848010-d7fe6bc767ec?w=800&auto=format&fit=crop&q=80',
                'fabric' => '320 GSM French Terry Cotton',
                'fit' => 'Relaxed Ankle Cuffed',
                'color' => 'Charcoal',
                'tag' => 'Joggers'
            ],
            [
                'name' => 'Desert Sand Wide-Leg Streetwear Cargo Trousers',
                'category_id' => $catMen->id,
                'mrp' => 1499,
                'image' => 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=800&auto=format&fit=crop&q=80',
                'fabric' => 'Cotton Linen Twill',
                'fit' => 'Wide Leg Baggy',
                'color' => 'Beige',
                'tag' => 'Wide Leg Pants'
            ],
            [
                'name' => 'Noir Street Parachute Windproof Track Pants',
                'category_id' => $catMen->id,
                'mrp' => 1399,
                'image' => 'https://images.unsplash.com/photo-1584370848010-d7fe6bc767ec?w=800&auto=format&fit=crop&q=80',
                'fabric' => 'Lightweight Matte Taslan Nylon',
                'fit' => 'Baggy Parachute Fit',
                'color' => 'Black',
                'tag' => 'Parachute Pants'
            ],
            [
                'name' => 'Raw Edge Vintage Washed Baggy Skate Denim',
                'category_id' => $catMen->id,
                'mrp' => 1499,
                'image' => 'https://images.unsplash.com/photo-1542272604-780c96856592?w=800&auto=format&fit=crop&q=80',
                'fabric' => '13.5 oz 100% Rigid Denim Cotton',
                'fit' => '90s Baggy Fit',
                'color' => 'Navy Blue',
                'tag' => 'Denim'
            ],

            // Women's Oversized Tees & Tops (26-35)
            [
                'name' => 'Y2K Cyber Angel Graphic Oversized Tee',
                'category_id' => $catWomen->id,
                'mrp' => 1199,
                'image' => 'https://images.unsplash.com/photo-1509631179647-0177331693ae?w=800&auto=format&fit=crop&q=80',
                'fabric' => '240 GSM Bio-Washed Combed Cotton',
                'fit' => 'Drop Shoulder Oversized Fit',
                'color' => 'Off White',
                'tag' => 'Y2K Streetwear'
            ],
            [
                'name' => 'Gothic Butterfly Vintage Acid Wash Baby Tee',
                'category_id' => $catWomen->id,
                'mrp' => 999,
                'image' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=800&auto=format&fit=crop&q=80',
                'fabric' => '200 GSM Ribbed Stretch Cotton',
                'fit' => 'Cropped Fitted Fit',
                'color' => 'Charcoal',
                'tag' => 'Baby Tee'
            ],
            [
                'name' => 'Tokyo Neon Blossom Back Print Oversized Tee',
                'category_id' => $catWomen->id,
                'mrp' => 1299,
                'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=800&auto=format&fit=crop&q=80',
                'fabric' => '240 GSM 100% Cotton',
                'fit' => 'Boxy Loose Fit',
                'color' => 'Black',
                'tag' => 'Oversized Tee'
            ],
            [
                'name' => 'Dusty Pink Minimalist Grunge Streetwear Tee',
                'category_id' => $catWomen->id,
                'mrp' => 1099,
                'image' => 'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?w=800&auto=format&fit=crop&q=80',
                'fabric' => '220 GSM Soft Terry Cotton',
                'fit' => 'Relaxed Oversized Fit',
                'color' => 'Dusty Pink',
                'tag' => 'Aesthetic Tee'
            ],
            [
                'name' => 'Ethereal Cyber Skeleton Boxy Crop Top',
                'category_id' => $catWomen->id,
                'mrp' => 999,
                'image' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=800&auto=format&fit=crop&q=80',
                'fabric' => '210 GSM Combed Cotton',
                'fit' => 'Boxy Crop Fit',
                'color' => 'Black',
                'tag' => 'Crop Top'
            ],
            [
                'name' => 'Pastel Sage Anime Aesthetic Oversized T-Shirt',
                'category_id' => $catWomen->id,
                'mrp' => 1199,
                'image' => 'https://images.unsplash.com/photo-1496747611176-843222e1e57c?w=800&auto=format&fit=crop&q=80',
                'fabric' => '240 GSM French Terry Cotton',
                'fit' => 'Loose Streetwear Fit',
                'color' => 'Sage',
                'tag' => 'Graphic Tee'
            ],
            [
                'name' => 'Noir Minimal Ribbed High-Neck Sleeveless Crop',
                'category_id' => $catWomen->id,
                'mrp' => 999,
                'image' => 'https://images.unsplash.com/photo-1509967419530-da38b4704bc6?w=800&auto=format&fit=crop&q=80',
                'fabric' => '95% Cotton 5% Spandex Rib',
                'fit' => 'Bodycon Street Fit',
                'color' => 'Black',
                'tag' => 'Ribbed Top'
            ],
            [
                'name' => 'Vintage Renaissance Streetwear Graphic Tee',
                'category_id' => $catWomen->id,
                'mrp' => 1299,
                'image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=800&auto=format&fit=crop&q=80',
                'fabric' => '240 GSM Bio-Washed Cotton',
                'fit' => 'Drop Shoulder Fit',
                'color' => 'Off White',
                'tag' => 'Graphic Tee'
            ],
            [
                'name' => 'Cobalt Cyber Grunge Boxy Oversized T-Shirt',
                'category_id' => $catWomen->id,
                'mrp' => 1199,
                'image' => 'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?w=800&auto=format&fit=crop&q=80',
                'fabric' => '240 GSM Heavy Cotton',
                'fit' => 'Boxy Fit',
                'color' => 'Cobalt',
                'tag' => 'Streetwear'
            ],
            [
                'name' => 'Y2K Rhinestone Star Street Crop Top',
                'category_id' => $catWomen->id,
                'mrp' => 1099,
                'image' => 'https://images.unsplash.com/photo-1509631179647-0177331693ae?w=800&auto=format&fit=crop&q=80',
                'fabric' => '200 GSM Cotton Lycra',
                'fit' => 'Fitted Crop Fit',
                'color' => 'Off White',
                'tag' => 'Crop Top'
            ],

            // Women's Hoodies, Co-ords & Bottoms (36-44)
            [
                'name' => 'Midnight Noir Raw Hem Cropped Streetwear Hoodie',
                'category_id' => $catWomen->id,
                'mrp' => 1499,
                'image' => 'https://images.unsplash.com/photo-1554412933-514a83d2f3c8?w=800&auto=format&fit=crop&q=80',
                'fabric' => '340 GSM Cotton Loopknit Fleece',
                'fit' => 'Cropped Boxy Hoodie',
                'color' => 'Black',
                'tag' => 'Crop Hoodie'
            ],
            [
                'name' => 'Dusty Pink Relaxed Wide-Leg Fleece Sweatpants',
                'category_id' => $catWomen->id,
                'mrp' => 1399,
                'image' => 'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?w=800&auto=format&fit=crop&q=80',
                'fabric' => '300 GSM French Terry Fleece',
                'fit' => 'High-Waist Wide Leg',
                'color' => 'Dusty Pink',
                'tag' => 'Sweatpants'
            ],
            [
                'name' => 'Parachute Low-Rise Streetwear Baggy Cargos',
                'category_id' => $catWomen->id,
                'mrp' => 1499,
                'image' => 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=800&auto=format&fit=crop&q=80',
                'fabric' => 'Matte Taslan Crinkle Poly',
                'fit' => 'Adjustable Toggle Baggy',
                'color' => 'Olive Green',
                'tag' => 'Cargo Pants'
            ],
            [
                'name' => 'Two-Piece Minimalist Streetwear Co-ord Set',
                'category_id' => $catWomen->id,
                'mrp' => 1499,
                'image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=800&auto=format&fit=crop&q=80',
                'fabric' => '280 GSM Cotton French Terry',
                'fit' => 'Boxy Top + Relaxed Shorts',
                'color' => 'Off White',
                'tag' => 'Co-ord Set'
            ],
            [
                'name' => 'Sage Bloom Oversized Zip-Up Fleece Hoodie',
                'category_id' => $catWomen->id,
                'mrp' => 1499,
                'image' => 'https://images.unsplash.com/photo-1554412933-514a83d2f3c8?w=800&auto=format&fit=crop&q=80',
                'fabric' => '360 GSM Heavyweight Fleece',
                'fit' => 'Oversized Fit',
                'color' => 'Sage',
                'tag' => 'Zip Hoodie'
            ],
            [
                'name' => 'Charcoal Noir High-Waist Wide Leg Cargo Pants',
                'category_id' => $catWomen->id,
                'mrp' => 1499,
                'image' => 'https://images.unsplash.com/photo-1584370848010-d7fe6bc767ec?w=800&auto=format&fit=crop&q=80',
                'fabric' => '100% Cotton Canvas Twill',
                'fit' => 'Wide Leg Baggy',
                'color' => 'Charcoal',
                'tag' => 'Cargo Pants'
            ],
            [
                'name' => 'Oversized Street Bomber Jacket with Contrast Rib',
                'category_id' => $catWomen->id,
                'mrp' => 1499,
                'image' => 'https://images.unsplash.com/photo-1576995853123-5a10305d93c0?w=800&auto=format&fit=crop&q=80',
                'fabric' => 'Insulated Matte Nylon Shell',
                'fit' => 'Drop Shoulder Bomber',
                'color' => 'Navy Blue',
                'tag' => 'Bomber Jacket'
            ],
            [
                'name' => 'Aesthetic Washed Lilac Cropped Sweatshirt',
                'category_id' => $catWomen->id,
                'mrp' => 1299,
                'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=800&auto=format&fit=crop&q=80',
                'fabric' => '320 GSM French Terry',
                'fit' => 'Cropped Loose Fit',
                'color' => 'Dusty Pink',
                'tag' => 'Sweatshirt'
            ],
            [
                'name' => 'Vintage Stone Wash Denim Trucker Jacket',
                'category_id' => $catWomen->id,
                'mrp' => 1499,
                'image' => 'https://images.unsplash.com/photo-1576995853123-5a10305d93c0?w=800&auto=format&fit=crop&q=80',
                'fabric' => '12 oz Washed Denim Cotton',
                'fit' => 'Oversized Boxy Trucker',
                'color' => 'Navy Blue',
                'tag' => 'Denim Jacket'
            ],

            // Unisex Drops & Street Accessories (45-50)
            [
                'name' => 'Vintage Acid Washed Cotton Bucket Hat',
                'category_id' => $catAccessories->id,
                'mrp' => 999,
                'image' => 'https://images.unsplash.com/photo-1576871337622-98d48d1cf531?w=800&auto=format&fit=crop&q=80',
                'fabric' => '100% Washed Heavy Cotton Twill',
                'fit' => 'One Size Fits All (58cm)',
                'color' => 'Black',
                'tag' => 'Bucket Hat'
            ],
            [
                'name' => 'Tactical Industrial Crossbody Streetwear Sling Bag',
                'category_id' => $catAccessories->id,
                'mrp' => 1299,
                'image' => 'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=800&auto=format&fit=crop&q=80',
                'fabric' => 'Heavy Duty 1000D Cordura Nylon',
                'fit' => 'Adjustable Webbed Strap',
                'color' => 'Black',
                'tag' => 'Crossbody Bag'
            ],
            [
                'name' => 'Chunky Ribbed Embroidered Streetwear Beanie',
                'category_id' => $catAccessories->id,
                'mrp' => 999,
                'image' => 'https://images.unsplash.com/photo-1576871337622-98d48d1cf531?w=800&auto=format&fit=crop&q=80',
                'fabric' => '100% Soft Acrylic Knit',
                'fit' => 'Snug Comfort Fit',
                'color' => 'Charcoal',
                'tag' => 'Beanie'
            ],
            [
                'name' => 'Minimalist Tokyo Syndicate Heavyweight Flannel Shirt',
                'category_id' => $catMen->id,
                'mrp' => 1499,
                'image' => 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=800&auto=format&fit=crop&q=80',
                'fabric' => '300 GSM Brushed Cotton Flannel',
                'fit' => 'Oversized Shacket Fit',
                'color' => 'Charcoal',
                'tag' => 'Flannel Shirt'
            ],
            [
                'name' => 'Retro College Varsity Streetwear Jacket',
                'category_id' => $catMen->id,
                'mrp' => 1499,
                'image' => 'https://images.unsplash.com/photo-1552374196-1ab2a1c593e8?w=800&auto=format&fit=crop&q=80',
                'fabric' => 'Wool Blend Body with Vegan Leather Sleeves',
                'fit' => 'Boxy Varsity Fit',
                'color' => 'Burgundy',
                'tag' => 'Varsity Jacket'
            ],
            [
                'name' => 'Vayu Limited Edition Signature Hoodie',
                'category_id' => $catMen->id,
                'mrp' => 1499,
                'image' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=800&auto=format&fit=crop&q=80',
                'fabric' => '400 GSM Heavyweight French Terry',
                'fit' => 'Signature Oversized Drop',
                'color' => 'Black',
                'tag' => 'Signature Edition'
            ]
        ];

        // Seed or Update all 50 Products
        $createdProducts = [];
        foreach ($productsData as $index => $item) {
            $sku = 'TTT-STREET-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            $slug = Str::slug($item['name']) . '-' . Str::lower(Str::random(4));

            $product = Product::updateOrCreate(
                ['sku' => $sku],
                [
                    'name' => $item['name'],
                    'slug' => $slug,
                    'category_id' => $item['category_id'],
                    'price' => 499.00, // Explicitly ₹499 for all
                    'original_price' => (float) $item['mrp'],
                    'cost_price' => 250.00,
                    'stock' => 120,
                    'image' => $item['image'],
                    'short_description' => 'Premium luxury streetwear drop crafted with ' . $item['fabric'] . ' in ' . $item['fit'] . '.',
                    'description' => '<h3>Vayu • LUXURY STREETWEAR</h3>' .
                        '<p>Crafted for the modern culture. This piece features a custom engineered silhouette with high-density pigment print and ultra-durable stitching.</p>' .
                        '<ul>' .
                        '<li><strong>Fabric:</strong> ' . $item['fabric'] . '</li>' .
                        '<li><strong>Fit:</strong> ' . $item['fit'] . '</li>' .
                        '<li><strong>Weight:</strong> Pre-shrunk high GSM luxury drape</li>' .
                        '<li><strong>Care:</strong> Cold machine wash inside-out, do not bleach, tumble dry low, iron on reverse</li>' .
                        '<li><strong>Dispatch:</strong> Ships within 24 hours with Express Delivery</li>' .
                        '</ul>',
                    'fabric' => $item['fabric'],
                    'fit' => $item['fit'],
                    'care_instructions' => 'Machine wash cold inside-out, tumble dry low, warm iron on reverse, do not iron on print.',
                    'is_active' => true,
                    'is_featured' => ($index % 3 == 0),
                    'is_trending' => ($index % 2 == 0),
                    'is_new' => ($index > 30),
                    'is_on_sale' => true,
                    'total_sold' => rand(80, 520)
                ]
            );

            $createdProducts[] = $product;

            // Attach Size Variants for Instant Buy / Add to Cart
            $targetColorId = $colorIds[$item['color']] ?? $colorIds['Black'];
            foreach ($sizesList as $sName) {
                ProductVariant::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'size' => $sName,
                        'color' => $item['color']
                    ],
                    [
                        'size_id' => $sizeIds[$sName] ?? null,
                        'color_id' => $targetColorId,
                        'color_hex' => $colorsList[$item['color']] ?? '#111111',
                        'sku' => $sku . '-' . $sName,
                        'price' => 499.00,
                        'original_price' => (float) $item['mrp'],
                        'cost_price' => 250.00,
                        'stock' => 25,
                        'is_active' => true
                    ]
                );
            }
        }

        // 5. Seed Coupons
        $coupons = [
            [
                'code' => 'TREND10',
                'description' => '10% Instant Flat Discount across Store',
                'type' => 'percentage',
                'value' => 10.00,
                'is_active' => true
            ],
            [
                'code' => 'SHARKTANK10',
                'description' => '10% Shark Tank Special Discount',
                'type' => 'percentage',
                'value' => 10.00,
                'is_active' => true
            ],
            [
                'code' => 'WELCOME50',
                'description' => 'Flat ₹50 Instant Off on your order',
                'type' => 'fixed',
                'value' => 50.00,
                'is_active' => true
            ],
            [
                'code' => 'STREET20',
                'description' => '20% Mega Savings on streetwear orders',
                'type' => 'percentage',
                'value' => 20.00,
                'is_active' => true
            ],
            [
                'code' => 'FIRST100',
                'description' => 'Flat ₹100 Off on First Purchase',
                'type' => 'fixed',
                'value' => 100.00,
                'is_active' => true
            ],
            [
                'code' => 'PREPAID5',
                'description' => '5% Extra Off on Online Payment',
                'type' => 'percentage',
                'value' => 5.00,
                'is_active' => true
            ]
        ];

        foreach ($coupons as $c) {
            Coupon::updateOrCreate(
                ['code' => $c['code']],
                [
                    'description' => $c['description'],
                    'type' => $c['type'],
                    'value' => $c['value'],
                    'is_active' => true
                ]
            );
        }

        // 6. Seed Real Talk / Verified Customer Reviews (15+ detailed streetwear reviews)
        $reviewsData = [
            [
                'name' => 'Aryan Sharma',
                'rating' => 5,
                'title' => 'Insane 240 GSM Quality!',
                'comment' => 'Honestly did not expect this level of heavyweight fabric at ₹499. The drop shoulder boxy fit is 10/10. Looks even better in real life than photos.',
                'tag' => 'Tokyo Matrix Cyberpunk Oversized Graphic Tee',
                'likes' => 142,
                'verified' => true
            ],
            [
                'name' => 'Sneha Kapoor',
                'rating' => 5,
                'title' => 'Perfect Aesthetic Fit',
                'comment' => 'The cropped hoodie fabric is super cozy and thick! Paired it with the cargo pants and got so many compliments at college today.',
                'tag' => 'Midnight Noir Raw Hem Cropped Streetwear Hoodie',
                'likes' => 98,
                'verified' => true
            ],
            [
                'name' => 'Kabir Mehta',
                'rating' => 5,
                'title' => 'Puff Print is legit',
                'comment' => 'The puff print quality is premium and does not peel off after washing. Delivery was super fast within 3 days in Mumbai. Ordering 2 more tees right now!',
                'tag' => 'Underground Syndicate High-Density Puff Print Tee',
                'likes' => 176,
                'verified' => true
            ],
            [
                'name' => 'Ananya Roy',
                'rating' => 5,
                'title' => 'Super Comfy & Aesthetic',
                'comment' => 'The baby tee fits like a dream! Soft cotton with good stretch. Loved the luxury packaging and the extra stickers too.',
                'tag' => 'Gothic Butterfly Vintage Acid Wash Baby Tee',
                'likes' => 84,
                'verified' => true
            ],
            [
                'name' => 'Rohan Varma',
                'rating' => 5,
                'title' => 'Best streetwear in India',
                'comment' => 'Been buying from international brands for years, but Vayu completely beats them in GSM quality and pricing. 5 stars all the way.',
                'tag' => 'Obsidian Stealth 380 GSM Heavyweight Street Hoodie',
                'likes' => 215,
                'verified' => true
            ],
            [
                'name' => 'Pooja Nair',
                'rating' => 5,
                'title' => 'Baggy Cargo Perfection',
                'comment' => 'The 8-pocket cargo pants are so functional and stylish. The waist fit is spot on and the fabric feels durable.',
                'tag' => 'Tactical 8-Pocket Relaxed Streetwear Cargo Pants',
                'likes' => 112,
                'verified' => true
            ],
            [
                'name' => 'Siddharth Roy',
                'rating' => 5,
                'title' => 'Vintage Acid Wash is on point',
                'comment' => 'The washed charcoal tone gives pure 90s rock band vibes. Collar rib is tight and holds shape after wash.',
                'tag' => 'Lost In Tokyo Vintage Acid Wash Graphic Tee',
                'likes' => 134,
                'verified' => true
            ],
            [
                'name' => 'Meera Deshmukh',
                'rating' => 5,
                'title' => 'Must-have co-ord set',
                'comment' => 'The quality of the French Terry fabric is top notch. Super breathable and feels very high-end for casual outings.',
                'tag' => 'Two-Piece Minimalist Streetwear Co-ord Set',
                'likes' => 91,
                'verified' => true
            ],
            [
                'name' => 'Varun Singhania',
                'rating' => 5,
                'title' => 'The hoodie weight is crazy',
                'comment' => '380 GSM is heavy! Keeps the hood standing perfectly without slouching. Easily worth 3x the price.',
                'tag' => 'Vayu Limited Edition Signature Hoodie',
                'likes' => 240,
                'verified' => true
            ],
            [
                'name' => 'Tanya Bhatia',
                'rating' => 5,
                'title' => 'Obsessed with the oversized fit',
                'comment' => 'Ordered size M for that relaxed street look. The fabric feels silky smooth yet heavy cotton. Very impressed.',
                'tag' => 'Y2K Cyber Angel Graphic Oversized Tee',
                'likes' => 77,
                'verified' => true
            ],
            [
                'name' => 'Aditya Kashyap',
                'rating' => 5,
                'title' => 'Techwear vibes are unmatched',
                'comment' => 'The tactical straps and utility pockets on this cargo jogger are awesome. Perfect streetwear drip.',
                'tag' => 'Cyberpunk Technical Strapped Jogger Cargos',
                'likes' => 153,
                'verified' => true
            ],
            [
                'name' => 'Riya Sen',
                'rating' => 5,
                'title' => 'Loved the pastel lilac shade',
                'comment' => 'Color looks identical to pictures. Super cute and aesthetic streetwear vibe for cafe dates.',
                'tag' => 'Aesthetic Washed Lilac Cropped Sweatshirt',
                'likes' => 88,
                'verified' => true
            ],
            [
                'name' => 'Dev Malhotra',
                'rating' => 5,
                'title' => 'Unreal quality for 499',
                'comment' => 'I rarely leave reviews, but this brand deserves it. The finish, stitching, and tags are 100% luxury level.',
                'tag' => 'Rebellion Gothic Typography Heavyweight Tee',
                'likes' => 195,
                'verified' => true
            ],
            [
                'name' => 'Kritika Joshi',
                'rating' => 5,
                'title' => 'Parachute pants are fire!',
                'comment' => 'The toggles at the ankles let you switch between wide leg and cuffed jogger in seconds. Great purchase.',
                'tag' => 'Parachute Low-Rise Streetwear Baggy Cargos',
                'likes' => 105,
                'verified' => true
            ],
            [
                'name' => 'Yuvraj Patel',
                'rating' => 5,
                'title' => 'Fast delivery & crisp print',
                'comment' => 'Got delivered in 2 days to Bangalore. The print clarity is razor sharp and fabric is super breathable.',
                'tag' => 'Neo Samurai Kanji Oversized Streetwear T-Shirt',
                'likes' => 128,
                'verified' => true
            ]
        ];

        Review::truncate();

        foreach ($reviewsData as $i => $r) {
            $matchingProduct = Product::where('name', 'like', '%' . substr($r['tag'], 0, 15) . '%')->first() ?? ($createdProducts[$i % count($createdProducts)] ?? null);

            Review::create([
                'product_id' => $matchingProduct ? $matchingProduct->id : null,
                'reviewer_name' => $r['name'],
                'reviewer_image_url' => 'https://ui-avatars.com/api/?name=' . urlencode($r['name']) . '&background=00285a&color=fff&size=100&bold=true',
                'rating' => $r['rating'],
                'title' => $r['title'],
                'comment' => $r['comment'],
                'product_tag' => $r['tag'],
                'likes' => $r['likes'],
                'is_verified' => true,
                'is_approved' => true,
                'is_featured' => true,
                'is_active' => true,
                'created_at' => now()->subDays(rand(1, 30))
            ]);
        }
    }
}

