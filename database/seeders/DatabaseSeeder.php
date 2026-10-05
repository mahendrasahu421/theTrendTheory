<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            CategorySeeder::class,
            SizeSeeder::class,
            ColorSeeder::class,
            // KarmaCollectionSeeder::class,
            SiteSettingSeeder::class,
            TrendingStoryAndReviewSeeder::class,
            // DemoProductSeeder::class,
        ]);

        // Orders/Payments migrations are handled separately via artisan migrate.


    }
}
