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
                'name' => "Men's",
                'slug' => 'men',
                'description' => "Luxury streetwear, oversized tees, hoodies, and cargo essentials for men.",
                'image' => 'https://images.unsplash.com/photo-1506630448388-4e683c67ddb0?w=1000&auto=format&fit=crop&q=85',
                'banner_image' => 'https://images.unsplash.com/photo-1509967419530-da38b4704bc6?w=1600&auto=format&fit=crop&q=85',
                'sort_order' => 1,
            ],
            [
                'name' => "Women's",
                'slug' => 'women',
                'description' => "Aesthetic street culture, crop baby tees, wide-leg cargos, and co-ords for women.",
                'image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=1000&auto=format&fit=crop&q=85',
                'banner_image' => 'https://images.unsplash.com/photo-1539109136881-3be0616acf4b?w=1600&auto=format&fit=crop&q=85',
                'sort_order' => 2,
            ],
            [
                'name' => "Oversized T-Shirts",
                'slug' => 'oversized-tees',
                'description' => "240 GSM Super Combed Cotton heavyweight graphic & vintage wash drop-shoulder tees.",
                'image' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=1000&auto=format&fit=crop&q=85',
                'banner_image' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=1600&auto=format&fit=crop&q=85',
                'sort_order' => 3,
            ],
            [
                'name' => "Hoodies & Sweatshirts",
                'slug' => 'hoodies-sweatshirts',
                'description' => "400 GSM French Terry Fleece oversized streetwear hoodies and crewnecks.",
                'image' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=1000&auto=format&fit=crop&q=85',
                'banner_image' => 'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?w=1600&auto=format&fit=crop&q=85',
                'sort_order' => 4,
            ],
            [
                'name' => "Cargos & Bottoms",
                'slug' => 'cargos-bottoms',
                'description' => "Tactical multi-pocket cargo pants, wide-leg skate denim, and parachute track pants.",
                'image' => 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=1000&auto=format&fit=crop&q=85',
                'banner_image' => 'https://images.unsplash.com/photo-1517445312882-bc9910d016b7?w=1600&auto=format&fit=crop&q=85',
                'sort_order' => 5,
            ],
            [
                'name' => "Jackets & Outerwear",
                'slug' => 'jackets-outerwear',
                'description' => "Bomber jackets, sherpa-lined winter coats, denim trucker jackets, and windbreakers.",
                'image' => 'https://images.unsplash.com/photo-1485230895905-ec40ba36b9bc?w=1000&auto=format&fit=crop&q=85',
                'banner_image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=1600&auto=format&fit=crop&q=85',
                'sort_order' => 6,
            ],
            [
                'name' => "Accessories & Drops",
                'slug' => 'accessories',
                'description' => "Streetwear sling bags, caps, bucket hats, and limited edition drop pieces.",
                'image' => 'https://images.unsplash.com/photo-1576871337622-98d48d1cf531?w=1000&auto=format&fit=crop&q=85',
                'banner_image' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=1600&auto=format&fit=crop&q=85',
                'sort_order' => 7,
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
                    'image' => $category['image'],
                    'banner_image' => $category['banner_image'],
                    'meta_title' => $category['name'] . ' Collection | THE TREND THEORY',
                    'meta_description' => 'Shop ' . strtolower($category['name']) . ' fashion at THE TREND THEORY.',
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
