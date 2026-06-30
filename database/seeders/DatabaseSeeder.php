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
            SizeSeeder::class,
            ColorSeeder::class,
            SiteSettingSeeder::class,
            DemoProductSeeder::class,
        ]);

        // Orders/Payments migrations are handled separately via artisan migrate.


    }
}
