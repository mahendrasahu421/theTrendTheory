<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizeSeeder extends Seeder
{
    public function run(): void
    {
        // Minimal default seed values for admin forms.
        // Adjust list as per your business needs.
        $sizes = [
            ['name' => 'XS', 'label' => 'XS', 'value' => 'XS', 'type' => 'clothing', 'is_active' => 1, 'sort_order' => 10],
            ['name' => 'S',  'label' => 'S',  'value' => 'S',  'type' => 'clothing', 'is_active' => 1, 'sort_order' => 20],
            ['name' => 'M',  'label' => 'M',  'value' => 'M',  'type' => 'clothing', 'is_active' => 1, 'sort_order' => 30],
            ['name' => 'L',  'label' => 'L',  'value' => 'L',  'type' => 'clothing', 'is_active' => 1, 'sort_order' => 40],
            ['name' => 'XL', 'label' => 'XL', 'value' => 'XL', 'type' => 'clothing', 'is_active' => 1, 'sort_order' => 50],
            ['name' => 'XXL', 'label' => 'XXL', 'value' => 'XXL', 'type' => 'clothing', 'is_active' => 1, 'sort_order' => 60],
            ['name' => '28', 'label' => 'Waist 28', 'value' => '28', 'type' => 'bottomwear', 'is_active' => 1, 'sort_order' => 110],
            ['name' => '30', 'label' => 'Waist 30', 'value' => '30', 'type' => 'bottomwear', 'is_active' => 1, 'sort_order' => 120],
            ['name' => '32', 'label' => 'Waist 32', 'value' => '32', 'type' => 'bottomwear', 'is_active' => 1, 'sort_order' => 130],
            ['name' => '34', 'label' => 'Waist 34', 'value' => '34', 'type' => 'bottomwear', 'is_active' => 1, 'sort_order' => 140],
            ['name' => '36', 'label' => 'Waist 36', 'value' => '36', 'type' => 'bottomwear', 'is_active' => 1, 'sort_order' => 150],
        ];

        foreach ($sizes as $row) {
            DB::table('sizes')->updateOrInsert(
                ['name' => $row['name']],
                [
                    'label' => $row['label'],
                    'value' => $row['value'],
                    'type' => $row['type'],
                    'is_active' => $row['is_active'],
                    'sort_order' => $row['sort_order'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}

