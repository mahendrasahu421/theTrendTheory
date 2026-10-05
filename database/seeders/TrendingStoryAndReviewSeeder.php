<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TrendingStory;
use App\Models\Review;
use App\Models\Product;

use App\Models\Blog;
use Illuminate\Support\Str;

class TrendingStoryAndReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. SEED TRENDING STORIES (THE VIBE)
        TrendingStory::truncate();

        $stories = [
            [
                'title' => 'Creatures of Paradise: Where the Wild Meets Streetwear',
                'caption' => 'Step into our latest drop, where wild animal graphics meet bold typography and heavy-duty 240 GSM combed cotton.',
                'caption_highlight' => 'WILD DROP',
                'image' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=900&auto=format&fit=crop&q=85',
                'user_name' => '@thetrendtheory',
                'user_avatar' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=120&auto=format&fit=crop&q=80',
                'badge' => 'TRENDING',
                'badge_type' => 'hot',
                'likes_count' => 2450,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Bars, Beats & Baggy Fits: When Indian Hip-Hop Meets Streetwear',
                'caption' => 'When underground Indian hip-hop culture meets boxy oversized silhouettes, the streets take notice. An unfiltered look at the movement.',
                'caption_highlight' => 'HUSTLE X TREND',
                'image' => 'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?w=900&auto=format&fit=crop&q=85',
                'user_name' => '@thetrendtheory',
                'user_avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=120&auto=format&fit=crop&q=80',
                'badge' => 'FEATURED',
                'badge_type' => 'exclusive',
                'likes_count' => 3820,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Types of Tops Worth Having in Your Wardrobe',
                'caption' => 'Explore our definitive streetwear guide to essential tops, from 240 GSM drop-shoulder tees to relaxed everyday cuts.',
                'caption_highlight' => 'STYLE GUIDE',
                'image' => 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?w=900&auto=format&fit=crop&q=85',
                'user_name' => '@thetrendtheory',
                'user_avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=120&auto=format&fit=crop&q=80',
                'badge' => 'NEW DROP',
                'badge_type' => 'default',
                'likes_count' => 1980,
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($stories as $storyData) {
            TrendingStory::create($storyData);
        }

        // 1.1 SEED BACKEND NEWS & PRESS (Blog model where type = 'news')
        $newsArticles = [
            [
                'title' => 'Creatures of Paradise: Where the Wild Meets Streetwear',
                'slug' => 'creatures-of-paradise-where-the-wild-meets-streetwear',
                'type' => 'news',
                'category' => 'Product Drops & Launches',
                'summary' => 'Step into our latest drop, where wild animal graphics meet bold typography and heavy-duty 240 GSM combed cotton.',
                'content' => "Step into our latest drop, where wild animal graphics meet bold typography and heavy-duty 240 GSM combed cotton.\n\nInspired by nocturnal wildlife, untamed landscapes, and nocturnal city energy, this limited capsule introduces custom graphic prints screen-printed on premium relaxed silhouettes. Each garment is enzyme-washed for a buttery soft hand feel while retaining structural drape.\n\nWhether paired with our relaxed cargo trousers or layered under an oversized zip-hoodie, Creatures of Paradise stands as our boldest statement yet for this season.",
                'image_url' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=900&auto=format&fit=crop&q=85',
                'author_name' => 'THE TREND THEORY Editorial',
                'read_time' => '3 min read',
                'tags' => 'Streetwear, Wild Drop, Oversized, Fashion Trends',
                'is_published' => true,
                'is_featured' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Bars, Beats & Baggy Fits: When Indian Hip-Hop Meets Streetwear',
                'slug' => 'bars-beats-baggy-fits-when-indian-hip-hop-meets-streetwear',
                'type' => 'news',
                'category' => 'Brand Collaborations',
                'summary' => 'When underground Indian hip-hop culture meets boxy oversized silhouettes, the streets take notice. An unfiltered look at the movement.',
                'content' => "When underground Indian hip-hop culture collided with raw oversized streetwear, a new wave of street luxury was born. An unfiltered look at the movement shaping cyphers, music videos, and everyday street fashion.\n\nFrom Mumbai gullies to international festival stages, artists and producers are trading cookie-cutter fits for heavyweight boxy tees, baggy cargos, and chain-link accessories that celebrate individuality, rhythm, and homegrown pride.\n\nOur collaborative drop captures this raw energy with high-density puff prints, dropped shoulders, and ultra-breathable combed terry cotton.",
                'image_url' => 'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?w=900&auto=format&fit=crop&q=85',
                'author_name' => 'THE TREND THEORY Editorial',
                'read_time' => '4 min read',
                'tags' => 'HipHop, Street Culture, Baggy Fits, Collaboration',
                'is_published' => true,
                'is_featured' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Types of Tops Worth Having in Your Wardrobe',
                'slug' => 'types-of-tops-worth-having-in-your-wardrobe',
                'type' => 'news',
                'category' => 'Press Release',
                'summary' => 'Explore our definitive streetwear guide to essential tops, from 240 GSM drop-shoulder tees to relaxed everyday cuts.',
                'content' => "Explore our definitive streetwear guide to essential tops, from 240 GSM drop-shoulder tees to relaxed everyday cuts.\n\nBuilding an adaptable wardrobe starts with understanding fit, fabric weight, and proportions. Discover how to style boxy tees, mock-neck long sleeves, heavyweight French Terry crewnecks, and breezy linen shirts to transition effortlessly between casual daytime aesthetics and late-night city outings.\n\nKey essentials include: 1) The Heavyweight Boxy Tee (240 GSM), 2) The Drop-Shoulder Sweatshirt, 3) The Relaxed Utility Overshirt, and 4) The Minimalist Ribbed Tank.",
                'image_url' => 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?w=900&auto=format&fit=crop&q=85',
                'author_name' => 'THE TREND THEORY Editorial',
                'read_time' => '3 min read',
                'tags' => 'Style Guide, Wardrobe Essentials, Tops, Streetwear',
                'is_published' => true,
                'is_featured' => false,
                'published_at' => now()->subDays(10),
            ],
        ];

        foreach ($newsArticles as $newsData) {
            Blog::updateOrCreate(
                ['slug' => $newsData['slug']],
                $newsData
            );
        }

        // 2. SEED REVIEWS (THE BUZZ / REAL TALK)
        Review::truncate();

        // Find products to associate if available
        $chandProduct = Product::where('slug', 'like', '%chand-pe-hai%')->first() ?? Product::first();
        $sadikiProduct = Product::where('slug', 'like', '%sadiki%')->first() ?? Product::find(2);
        $royalProduct = Product::where('slug', 'like', '%royal%')->first() ?? Product::find(4);

        $reviews = [
            [
                'product_id' => $chandProduct ? $chandProduct->id : null,
                'reviewer_name' => 'Aryan Sharma',
                'reviewer_image_url' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=150&auto=format&fit=crop&q=80',
                'rating' => 5,
                'title' => 'Best heavyweight tee in my closet',
                'comment' => 'The 240 GSM fabric is thick, structured, and does not lose shape after multiple washes. The boxy drop-shoulder cut sits perfectly on the frame.',
                'product_tag' => 'Chand Pe Hai Apun Tee • Off White',
                'likes' => 48,
                'is_verified' => true,
                'is_approved' => true,
                'is_featured' => true,
                'is_active' => true,
                'review_media' => [
                    'https://res.cloudinary.com/dsrseyrku/image/upload/v1790657880/products/94c9b0ae-5f23-4095-b9ae-348c33b26dc7-1790657878.webp'
                ],
            ],
            [
                'product_id' => $sadikiProduct ? $sadikiProduct->id : null,
                'reviewer_name' => 'Pooja Kashyap',
                'reviewer_image_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',
                'rating' => 5,
                'title' => 'Top-tier graphics and color depth',
                'comment' => 'The print quality on the Sadiki Elephant tee is insane. Crisp lines, zero rubbery feel, and ultra soft combed cotton. Got endless compliments in college.',
                'product_tag' => 'Sadiki Elephant Tee • Black',
                'likes' => 39,
                'is_verified' => true,
                'is_approved' => true,
                'is_featured' => true,
                'is_active' => true,
                'review_media' => [
                    'https://res.cloudinary.com/dsrseyrku/image/upload/v1790763134/products/2/front/5b33b040-9ce3-4ddc-9c90-f1a683f34466-1790763128.webp'
                ],
            ],
            [
                'product_id' => $royalProduct ? $royalProduct->id : null,
                'reviewer_name' => 'Devendra Roy',
                'reviewer_image_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80',
                'rating' => 5,
                'title' => 'Delivered in 36 hours. Elite fit',
                'comment' => 'Hard to find authentic streetwear brands in India that actually get boxy drop-shoulder proportions right. The Trend Theory nailed it completely.',
                'product_tag' => 'Royal Serpent Oversized Tee',
                'likes' => 31,
                'is_verified' => true,
                'is_approved' => true,
                'is_featured' => true,
                'is_active' => true,
                'review_media' => [
                    'https://res.cloudinary.com/dsrseyrku/image/upload/v1790765188/products/4/front/09da5cde-96e4-47f8-aca9-cd70aacbb28e-1790765187.webp'
                ],
            ],
        ];

        foreach ($reviews as $rev) {
            Review::create($rev);
        }
    }
}
