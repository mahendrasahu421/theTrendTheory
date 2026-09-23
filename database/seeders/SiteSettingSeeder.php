<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name' => 'THE TREND THEORY',
            'site_tagline' => 'Breathe The Trend — Premium Streetwear',
            'address' => 'India',
            'phone' => '+91 7800789705',
            'email' => 'support@THE TREND THEORY.com',
            'currency' => 'INR',
            'currency_symbol' => 'Rs.',
            'shipping_free_above' => '999',
            'return_days' => '7',
            'min_order_amount' => '299',
            'tax_rate' => '5',
            'shipping_rate' => '79',
            'meta_title' => 'THE TREND THEORY | Premium Fashion Store India',
            'meta_description' => 'Shop modern streetwear, premium basics, dresses, shirts, and everyday essentials at THE TREND THEORY.',
            'meta_keywords' => 'THE TREND THEORY, streetwear, fashion, premium basics, shirts, dresses, India',
            'facebook_url' => 'https://facebook.com/thetrendtheory',
            'instagram_url' => 'https://instagram.com/thetrendtheory',
            'whatsapp_number' => '+91 7800789705',
            'timezone' => 'Asia/Kolkata',
            'robots_txt' => "User-agent: *\nAllow: /",
        ];

        foreach ($settings as $key => $value) {
            DB::table('site_settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => now(), 'created_at' => now()]
            );
        }

        Cache::forget('site_settings_array');
        Cache::forget('site_settings_collection');
    }
}
