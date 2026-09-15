<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\ProductImage;

class HundredProductsSeeder extends Seeder
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
            'Sage Green' => '#8A9A86',
            'Dusty Pink' => '#D4A5A5',
            'Cobalt Blue' => '#1E40AF',
            'Beige' => '#D2B48C',
            'Vintage Grey' => '#4A4A4A',
            'Forest Green' => '#1C3A27'
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
                'name' => "Men's",
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
                'name' => "Women's",
                'description' => 'Aesthetic street culture, crop hoodies, co-ords, and wide-leg cargos for women.',
                'image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=800&auto=format&fit=crop&q=80',
                'is_active' => true,
                'show_in_nav' => true,
                'show_in_home' => true,
                'sort_order' => 2
            ]
        );

        // Curated List of High Quality Unsplash Fashion Images
        $menImages = [
            'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1618354691229-88d47f285158?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1509967419530-da38b4704bc6?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1516257984-b1b4d707412e?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1552374196-1ab2a1c593e8?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1488161628813-04466f872be2?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=800&auto=format&fit=crop&q=80'
        ];

        $womenImages = [
            'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1539109136881-3be0616acf4b?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1509631179647-0177331693ae?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1485230895905-ec40ba36b9bc?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1558769132-cb1aea458c5e?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1469334031218-e382a71b716b?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1502716119720-b23a93e5fe1b?w=800&auto=format&fit=crop&q=80'
        ];

        // 100 Unique Streetwear Product Titles
        $rawProducts = [
            // 50 MEN'S PRODUCTS
            ["Tokyo Matrix Cyberpunk Oversized Graphic Tee", $catMen->id, 499, 1299, "240 GSM 100% Super Combed Cotton", "Drop Shoulder Oversized Fit", "Black"],
            ["Acid Wash Vintage Nirvana Heavyweight T-Shirt", $catMen->id, 599, 1499, "260 GSM Terry Cotton Fabric", "Boxy Vintage Drop Fit", "Vintage Grey"],
            ["Cybernetic Samurai Japanese Kanji Streetwear Tee", $catMen->id, 499, 1199, "220 GSM Bio-Washed Cotton", "Relaxed Street Fit", "Off White"],
            ["Gothic Obsidian Skull Print Boxy Tee", $catMen->id, 499, 1399, "240 GSM Heavy Cotton", "Boxy Drop Shoulder", "Black"],
            ["Vaporwave Retro Neon Sunset Oversized Tee", $catMen->id, 499, 1299, "240 GSM 100% Cotton", "Oversized Fit", "Navy Blue"],
            ["Midnight Void Minimalist Back-Print Tee", $catMen->id, 499, 1199, "240 GSM Bio-Washed", "Boxy Fit", "Charcoal"],
            ["Metropolis Future City Cyberpunk Tee", $catMen->id, 499, 1299, "240 GSM Combed Cotton", "Drop Shoulder Fit", "Cobalt Blue"],
            ["Kyoto Cherry Blossom Kanji Heavyweight Tee", $catMen->id, 549, 1399, "260 GSM French Terry", "Oversized Fit", "Off White"],
            ["Dark Matter Acid Washed Vintage Boxy Tee", $catMen->id, 599, 1499, "240 GSM Vintage Washed Cotton", "Loose Boxy Fit", "Charcoal"],
            ["Chrome Liquid Metal Streetwear Tee", $catMen->id, 499, 1299, "240 GSM 100% Cotton", "Boxy Drop Shoulder", "Black"],
            ["Urban Tactical Multi-Pocket Cargo Pants", $catMen->id, 1299, 2499, "Cotton Twill 320 GSM", "Relaxed Tapered Fit", "Black"],
            ["Nomad Heavyweight Parachute Track Pants", $catMen->id, 1199, 2299, "Micro Ripstop Nylon", "Wide Leg Balloon Fit", "Olive Green"],
            ["Concrete Jungle Streetwear Zipper Cargo Pants", $catMen->id, 1399, 2699, "Heavy Duck Canvas Cotton", "Loose Tactical Fit", "Charcoal"],
            ["Desert Storm Sand Cargo Utility Joggers", $catMen->id, 1199, 2199, "Stretch Cotton Twill", "Slim Tapered Cargo Fit", "Beige"],
            ["Cyber Grid Techwear Relaxed Cargo Pants", $catMen->id, 1499, 2899, "Water Repellent Polyester Canvas", "Relaxed Fit", "Black"],
            ["Heavyweight 400 GSM Obsidian Pullover Hoodie", $catMen->id, 1299, 2799, "400 GSM Super Combed French Terry", "Drop Shoulder Boxy Hoodie", "Black"],
            ["Tokyo Neon Nightdrive Graphic Street Hoodie", $catMen->id, 1399, 2999, "380 GSM Heavy French Terry", "Oversized Streetwear Fit", "Charcoal"],
            ["Vintage Acid Wash Distressed Skate Hoodie", $catMen->id, 1499, 3199, "400 GSM Vintage Acid Washed Terry", "Relaxed Boxy Fit", "Vintage Grey"],
            ["Cyberpunk Kanji Heavyweight Zip-Up Hoodie", $catMen->id, 1599, 3499, "420 GSM 100% Terry Cotton", "Full Zip Boxy Fit", "Black"],
            ["Forest Pine Embroidered Minimalist Hoodie", $catMen->id, 1299, 2699, "380 GSM Bio-Washed Cotton", "Classic Street Fit", "Forest Green"],
            ["Cobalt Horizon Heavy Boxy Crewneck Sweatshirt", $catMen->id, 999, 2199, "360 GSM Brushed Fleece", "Drop Shoulder Fit", "Cobalt Blue"],
            ["Concrete Grey Raw-Hem Street Sweatshirt", $catMen->id, 999, 2099, "380 GSM French Terry", "Raw Hem Boxy Fit", "Vintage Grey"],
            ["Blackout Tactical Utility Bomber Jacket", $catMen->id, 1999, 3999, "Nylon Shell with Satin Lining", "Relaxed Bomber Fit", "Black"],
            ["Vintage Washed Denim Trucker Jacket", $catMen->id, 1899, 3799, "14 Oz Rigid Denim", "Oversized Vintage Fit", "Vintage Grey"],
            ["Techwear Waterproof Hooded Windbreaker", $catMen->id, 1699, 3299, "Matte Nylon Waterproof", "Sport Street Fit", "Navy Blue"],
            ["Hyperdrive Minimalist Boxy Heavy Tee", $catMen->id, 499, 1199, "240 GSM Combed Cotton", "Boxy Drop Shoulder", "Black"],
            ["Genesis Abstract Typography Oversized Tee", $catMen->id, 499, 1299, "240 GSM Bio-Washed Cotton", "Relaxed Fit", "Off White"],
            ["Solar Flare Acid Orange Graphic Tee", $catMen->id, 499, 1299, "240 GSM Super Combed Cotton", "Drop Shoulder Fit", "Charcoal"],
            ["Shibuya Midnight Express Kanji Tee", $catMen->id, 549, 1399, "260 GSM Heavy Terry", "Oversized Street Fit", "Black"],
            ["Phantom Mirage Reflective 3M Street Tee", $catMen->id, 599, 1499, "240 GSM with 3M Reflective", "Boxy Fit", "Black"],
            ["Rapture Heavyweight Oversized Tee", $catMen->id, 499, 1199, "240 GSM Combed Cotton", "Drop Shoulder", "Sage Green"],
            ["Elysium Cloud Wash Tie-Dye Street Tee", $catMen->id, 549, 1399, "240 GSM Acid Cloud Wash", "Boxy Relaxed Fit", "Navy Blue"],
            ["Apex Cyber Predator Backprint Tee", $catMen->id, 499, 1299, "240 GSM 100% Cotton", "Drop Shoulder Fit", "Black"],
            ["Monolith Raw Seam Heavyweight Tee", $catMen->id, 549, 1399, "280 GSM Heavy Terry", "Raw Seam Boxy Fit", "Charcoal"],
            ["Vortex Distortion Graphic Skate Tee", $catMen->id, 499, 1299, "240 GSM Bio-Washed", "Oversized Fit", "Off White"],
            ["Zero Gravity Astronaut Graphic Oversized Tee", $catMen->id, 499, 1299, "240 GSM 100% Cotton", "Boxy Fit", "Navy Blue"],
            ["Kitsune Cyber Spirit Japanese Fox Tee", $catMen->id, 549, 1399, "240 GSM Combed Cotton", "Drop Shoulder Fit", "Black"],
            ["Rave Culture 90s Neon Acid Print Tee", $catMen->id, 499, 1199, "220 GSM Bio-Washed Cotton", "Street Fit", "Vintage Grey"],
            ["Subzero Nordic Minimalist Clean Tee", $catMen->id, 499, 1299, "240 GSM Organic Cotton", "Relaxed Boxy Fit", "Off White"],
            ["Inferno Dragon Embroidered Heavyweight Tee", $catMen->id, 599, 1499, "260 GSM with High-Density Embroidery", "Drop Shoulder Fit", "Black"],
            ["Tactical Carpenter Skate Wide-Leg Jeans", $catMen->id, 1499, 2999, "13.5 Oz 100% Cotton Denim", "Baggy Wide Leg Skate Fit", "Vintage Grey"],
            ["Ripped Distressed Vintage Baggy Denim", $catMen->id, 1599, 3199, "14 Oz Washed Denim", "Baggy Fit", "Charcoal"],
            ["Olive Military Fatigue Utility Trousers", $catMen->id, 1299, 2499, "100% Cotton Drill", "Straight Utility Fit", "Olive Green"],
            ["Off-Grid Heavy Canvas Worker Pants", $catMen->id, 1399, 2799, "Heavy Canvas Fabric", "Relaxed Fit", "Beige"],
            ["Minimalist Heavyweight Drop-Shoulder Polo", $catMen->id, 799, 1699, "260 GSM Pique Cotton", "Boxy Street Polo", "Black"],
            ["Velvet Touch Heavy Fleece Street Hoodie", $catMen->id, 1399, 2899, "400 GSM Plush Fleece", "Oversized Fit", "Burgundy"],
            ["Glitch Art Retro Cyberpunk Hoodie", $catMen->id, 1399, 2999, "380 GSM Heavy Cotton", "Boxy Fit", "Black"],
            ["Nomad Flannel Plaid Overshirt Jacket", $catMen->id, 1499, 2899, "Heavyweight Flannel Cotton", "Relaxed Overshirt Fit", "Navy Blue"],
            ["Corduroy Vintage Sherpa Lined Jacket", $catMen->id, 2199, 4299, "100% Cotton Corduroy with Sherpa", "Boxy Winter Fit", "Beige"],
            ["Streetwear Quilted Lightweight Puffer Vest", $catMen->id, 1599, 3299, "Water Resistant Matte Poly", "Puffer Vest Fit", "Black"],

            // 50 WOMEN'S PRODUCTS
            ["Aesthetic Butterfly Cyber Graphic Crop Tee", $catWomen->id, 449, 1099, "220 GSM Bio-Washed Cotton", "Baby Tee / Crop Fit", "Black"],
            ["Vintage Nirvana Acid Wash Oversized Boyfriend Tee", $catWomen->id, 499, 1299, "240 GSM Super Combed Cotton", "Ultra Oversized Fit", "Charcoal"],
            ["Cherry Blossom Anime Japanese Streetwear Tee", $catWomen->id, 499, 1199, "240 GSM 100% Cotton", "Boxy Drop Shoulder", "Off White"],
            ["Gothic Angel Wings Rhinestone Baby Tee", $catWomen->id, 449, 1099, "95% Cotton 5% Spandex", "Fitted Y2K Baby Tee", "Black"],
            ["Euphoria Purple Haze Oversized Graphic Tee", $catWomen->id, 499, 1299, "240 GSM Combed Cotton", "Drop Shoulder Fit", "Dusty Pink"],
            ["Cyber Girl 2077 Retro Future Graphic Tee", $catWomen->id, 499, 1199, "240 GSM Bio-Washed", "Oversized Street Fit", "Off White"],
            ["Dark Academia Minimalist Typography Tee", $catWomen->id, 499, 1199, "240 GSM 100% Cotton", "Relaxed Fit", "Sage Green"],
            ["Y2K Star Patchwork Washed Boxy Tee", $catWomen->id, 499, 1299, "240 GSM Vintage Washed Cotton", "Boxy Fit", "Navy Blue"],
            ["Liquid Chrome Heart Metallic Print Tee", $catWomen->id, 499, 1299, "240 GSM Cotton", "Drop Shoulder", "Black"],
            ["Sun Daze Aesthetic Retro Oversized Tee", $catWomen->id, 499, 1199, "240 GSM Bio-Washed", "Oversized Boyfriend Fit", "Beige"],
            ["Wide Leg High-Waist Parachute Cargo Pants", $catWomen->id, 1199, 2399, "Lightweight Ripstop Fabric", "Wide Leg Parachute Fit", "Olive Green"],
            ["Utility Multi-Pocket Streetwear Cargo Pants", $catWomen->id, 1299, 2599, "Cotton Twill 280 GSM", "Relaxed Straight Fit", "Black"],
            ["Vintage Baggy Carpenter Cargo Jeans", $catWomen->id, 1499, 2999, "13 Oz 100% Cotton Denim", "Baggy High Rise Fit", "Vintage Grey"],
            ["Sage Green Minimalist Drawstring Cargo Pants", $catWomen->id, 1199, 2299, "Soft Washed Cotton Twill", "Baggy Fit", "Sage Green"],
            ["Y2K Low Rise Techno Cargo Track Pants", $catWomen->id, 1299, 2499, "Matte Nylon Fabric", "Low Rise Wide Leg", "Black"],
            ["Heavyweight 400 GSM Cropped Street Hoodie", $catWomen->id, 1199, 2499, "400 GSM Super Combed French Terry", "Cropped Drop Shoulder", "Charcoal"],
            ["Oversized Cloud Wash Fleece Hoodie", $catWomen->id, 1399, 2899, "380 GSM Heavyweight Terry", "Ultra Oversized Fit", "Dusty Pink"],
            ["Tokyo Cyber City Backprint Hoodie", $catWomen->id, 1399, 2999, "380 GSM French Terry", "Boyfriend Fit", "Black"],
            ["Vintage Washed Zip-Up Aesthetic Hoodie", $catWomen->id, 1499, 3199, "400 GSM Acid Washed Cotton", "Boxy Full Zip Fit", "Vintage Grey"],
            ["Sage Botanical Minimalist Embroidered Hoodie", $catWomen->id, 1299, 2699, "380 GSM Bio-Washed Cotton", "Relaxed Fit", "Sage Green"],
            ["2-Piece Ribbed Crop Top & Cargo Co-ord Set", $catWomen->id, 1499, 2999, "Premium Rib Knit & Cotton Twill", "Fitted Top & Relaxed Pants", "Beige"],
            ["Washed Denim Cropped Jacket & Skirt Set", $catWomen->id, 1899, 3699, "12.5 Oz Vintage Denim", "Cropped Fit", "Vintage Grey"],
            ["Oversized Boyfriend Denim Jacket", $catWomen->id, 1799, 3499, "100% Heavy Denim", "Oversized Fit", "Navy Blue"],
            ["Streetwear Cropped Quilted Bomber Jacket", $catWomen->id, 1699, 3299, "Satin Poly Shell with Padding", "Cropped Bomber Fit", "Black"],
            ["Minimalist Relaxed Fleece Sweatshirt", $catWomen->id, 899, 1899, "340 GSM Brushed Fleece", "Drop Shoulder", "Off White"],
            ["Pastel Dream Aesthetic Oversized Sweatshirt", $catWomen->id, 999, 2099, "360 GSM French Terry", "Oversized Fit", "Dusty Pink"],
            ["Midnight Star Constellation Graphic Tee", $catWomen->id, 499, 1199, "240 GSM Combed Cotton", "Drop Shoulder Fit", "Black"],
            ["Ethereal Moon Phase Aesthetic Tee", $catWomen->id, 499, 1199, "240 GSM Bio-Washed", "Relaxed Boxy Fit", "Off White"],
            ["Cherry Graphic Ribbed Y2K Baby Tee", $catWomen->id, 449, 999, "95% Cotton 5% Elastane", "Fitted Crop", "Off White"],
            ["Cyber Dragon Holographic Print Tee", $catWomen->id, 499, 1299, "240 GSM Cotton", "Oversized Fit", "Black"],
            ["Urban Nomad High-Waisted Flare Trousers", $catWomen->id, 1299, 2499, "Stretch Ponte Roma Fabric", "High Rise Flare Fit", "Black"],
            ["Wide Leg Pleated Streetwear Trousers", $catWomen->id, 1399, 2799, "Premium Suiting Fabric", "High Waist Wide Leg", "Charcoal"],
            ["Vintage Washed Grunge Knit Cardigan", $catWomen->id, 1499, 2999, "Soft Acrylic Wool Blend", "Relaxed Chunky Knit", "Sage Green"],
            ["Oversized Striped Skater Knit Sweater", $catWomen->id, 1399, 2799, "Heavy Gauge Cotton Knit", "Boxy Skater Fit", "Black"],
            ["Mesh Layered Cyberpunk Long Sleeve Tee", $catWomen->id, 699, 1499, "Cotton Jersey & Stretch Mesh", "Double Layer Fit", "Black"],
            ["Tie-Dye Acid Washed Crop Hoodie", $catWomen->id, 1199, 2399, "360 GSM French Terry", "Cropped Relaxed", "Navy Blue"],
            ["Street Heat Flame Graphic Boxy Tee", $catWomen->id, 499, 1199, "240 GSM 100% Cotton", "Boxy Fit", "Black"],
            ["Minimalist Tone-on-Tone Embroidered Tee", $catWomen->id, 499, 1199, "240 GSM Organic Cotton", "Relaxed Fit", "Beige"],
            ["Harajuku Kawaii Streetwear Graphic Tee", $catWomen->id, 499, 1299, "240 GSM Combed Cotton", "Oversized Fit", "Dusty Pink"],
            ["Acid Wash Streetwear Denim Midi Skirt", $catWomen->id, 1299, 2499, "13 Oz Denim with Front Slit", "High Rise Midi Fit", "Vintage Grey"],
            ["Tactical Utility Mini Skirt with Belt", $catWomen->id, 999, 1999, "Cotton Twill 280 GSM", "A-Line Utility Fit", "Black"],
            ["Pastel Lilac Oversized French Terry Hoodie", $catWomen->id, 1299, 2699, "380 GSM Heavyweight Terry", "Oversized Fit", "Dusty Pink"],
            ["Velvet Street Crop Track Jacket", $catWomen->id, 1399, 2799, "Plush Velour Fabric", "Cropped Track Fit", "Burgundy"],
            ["Retro 70s Striped Vintage Baby Tee", $catWomen->id, 449, 999, "Cotton Ribbed Knit", "Fitted Crop Fit", "Beige"],
            ["Neon Cyberpunk High-Neck Bodysuit", $catWomen->id, 699, 1499, "Double Layered Lycra", "Snug Bodysuit Fit", "Black"],
            ["Oversized Vintage Leatherette Biker Jacket", $catWomen->id, 2299, 4499, "Premium Faux Leather with Quilted Lining", "Boyfriend Biker Fit", "Black"],
            ["Cozy Sherpa Fleece Zip-Up Streetwear Jacket", $catWomen->id, 1799, 3499, "Plush Heavyweight Sherpa", "Oversized Cozy Fit", "Off White"],
            ["High-Waisted Baggy Cargo Sweatpants", $catWomen->id, 1099, 2199, "340 GSM Terry Cotton", "Baggy Cuffed Fit", "Sage Green"],
            ["Minimalist Raw Edge French Terry Shorts", $catWomen->id, 699, 1399, "320 GSM French Terry", "Relaxed Street Shorts", "Charcoal"],
            ["Vayu Signature Heritage Street Hoodie", $catWomen->id, 1399, 2899, "400 GSM Super Combed Cotton", "Signature Boxy Fit", "Black"]
        ];

        $totalInserted = 0;
        $mIdx = 0;
        $wIdx = 0;

        foreach ($rawProducts as $index => $item) {
            $name = $item[0];
            $categoryId = $item[1];
            $price = $item[2];
            $originalPrice = $item[3];
            $fabric = $item[4];
            $fit = $item[5];
            $colorName = $item[6];

            // Pick realistic matching high quality image
            if ($categoryId === $catMen->id) {
                $mainImg = $menImages[$mIdx % count($menImages)];
                $mIdx++;
            } else {
                $mainImg = $womenImages[$wIdx % count($womenImages)];
                $wIdx++;
            }

            $slug = Str::slug($name);
            $sku = 'TTT-PROD-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);

            // Check if slug exists to avoid collisions
            $existingCount = Product::where('slug', $slug)->count();
            if ($existingCount > 0) {
                $slug .= '-' . ($index + 1);
            }

            $product = Product::create([
                'category_id'       => $categoryId,
                'name'              => $name,
                'slug'              => $slug,
                'sku'               => $sku,
                'price'             => $price,
                'original_price'    => $originalPrice,
                'cost_price'        => round($price * 0.45, 2),
                'image'             => $mainImg,
                'stock'             => rand(40, 150),
                'has_variants'      => true,
                'low_stock_alert'   => 10,
                'total_sold'        => rand(15, 300),
                'fabric'            => $fabric,
                'fit'               => $fit,
                'care_instructions' => 'Machine wash cold with similar colours. Do not iron directly on print. Tumble dry low.',
                'short_description' => "Engineered for pure street aesthetics. Made from premium {$fabric} with a tailored {$fit}.",
                'description'       => "Elevate your streetwear wardrobe with the {$name}. Designed by Vayu, this piece merges heavyweight premium comfort with sharp contemporary silhouettes. Featuring {$fabric}, {$fit}, reinforced seams, and luxury garment-washed finish.",
                'meta_title'        => "{$name} | Vayu",
                'meta_description'  => "Buy {$name} online at Vayu. Premium streetwear, fast shipping across India.",
                'is_active'         => true,
                'is_featured'       => ($index % 5 === 0),
                'is_new'            => ($index % 3 === 0),
                'is_trending'       => ($index % 4 === 0),
                'is_on_sale'        => ($originalPrice > $price),
                'created_at'        => now()->subDays(rand(1, 45)),
                'updated_at'        => now()
            ]);

            // Create Variants for Sizes S, M, L, XL, XXL
            $colorId = $colorIds[$colorName] ?? $colorIds['Black'];
            foreach ($sizesList as $sName) {
                $sId = $sizeIds[$sName];
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size_id'    => $sId,
                    'color_id'   => $colorId,
                    'sku'        => "{$sku}-{$sName}",
                    'price'      => $price,
                    'stock'      => rand(10, 35),
                    'is_active'  => true,
                ]);
            }

            // Create Product Image Gallery
            ProductImage::create([
                'product_id' => $product->id,
                'url'        => $mainImg,
                'alt_text'   => $name,
                'is_primary' => true,
                'sort_order' => 1
            ]);

            // Add 1-2 Reviews per product
            $reviewUsers = ['Aarav Sharma', 'Rohan Mehta', 'Sneha Kapoor', 'Ananya Roy', 'Kabir Verma', 'Pooja Patel', 'Vicky Malhotra', 'Tanvi Joshi'];
            $reviewComments = [
                'The quality of the fabric is insane! Super heavyweight and fits perfectly.',
                'Best streetwear brand in India right now. The drop shoulder fit is on point.',
                'Fabric is thick and breathable. Print quality is very crisp. 10/10 recommend!',
                'Loved the packaging and delivery was super fast. True to size!',
                'Obsessed with this oversized fit. Received tons of compliments!'
            ];

            Review::create([
                'product_id'    => $product->id,
                'reviewer_name' => $reviewUsers[array_rand($reviewUsers)],
                'rating'        => rand(4, 5),
                'title'         => 'Superb Quality & Fit',
                'comment'       => $reviewComments[array_rand($reviewComments)],
                'is_verified'   => true,
                'is_approved'   => true,
                'is_active'     => true,
                'created_at'    => now()->subDays(rand(1, 20)),
                'updated_at'    => now(),
            ]);

            $totalInserted++;
        }

        $this->command->info("Successfully seeded {$totalInserted} high-quality products!");
    }
}
