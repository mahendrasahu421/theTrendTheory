<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizeSeeder extends Seeder
{
    public function run(): void
    {
        $sizes = [
            ['name' => 'M', 'label' => 'M', 'value' => 'M', 'type' => 'clothing', 'chest' => '42 inches', 'waist' => '40 inches', 'length' => '28 inches', 'is_active' => 1, 'sort_order' => 20],
            ['name' => 'L', 'label' => 'L', 'value' => 'L', 'type' => 'clothing', 'chest' => '44 inches', 'waist' => '42 inches', 'length' => '29 inches', 'is_active' => 1, 'sort_order' => 30],
            ['name' => 'XL', 'label' => 'XL', 'value' => 'XL', 'type' => 'clothing', 'chest' => '46 inches', 'waist' => '44 inches', 'length' => '30 inches', 'is_active' => 1, 'sort_order' => 40],
            ['name' => 'Oversized Tshirt', 'label' => 'Oversized Tshirt', 'value' => 'Oversized Tshirt', 'type' => 'clothing', 'chest' => '38 inches', 'waist' => '32 inches', 'length' => null, 'is_active' => 1, 'sort_order' => 10],
        ];

        foreach ($sizes as $row) {
            DB::table('sizes')->updateOrInsert(
                ['name' => $row['name']],
                [
                    'label' => $row['label'],
                    'value' => $row['value'],
                    'type' => $row['type'],
                    'chest' => $row['chest'],
                    'waist' => $row['waist'],
                    'length' => $row['length'],
                    'is_active' => $row['is_active'],
                    'sort_order' => $row['sort_order'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}

