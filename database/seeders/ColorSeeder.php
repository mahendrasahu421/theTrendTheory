<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['name' => 'Offwhite', 'label' => 'Offwhite', 'value' => 'Offwhite', 'type' => 'solid', 'hex' => '#F8F4E8', 'is_active' => 1, 'sort_order' => 10],
            ['name' => 'Black', 'label' => 'Black', 'value' => 'Black', 'type' => 'solid', 'hex' => '#111111', 'is_active' => 1, 'sort_order' => 20],
            ['name' => 'Brown', 'label' => 'Brown', 'value' => 'Brown', 'type' => 'solid', 'hex' => '#7A4A2E', 'is_active' => 1, 'sort_order' => 30],
            ['name' => 'Lavender', 'label' => 'Lavender', 'value' => 'Lavender', 'type' => 'solid', 'hex' => '#BBA7D9', 'is_active' => 1, 'sort_order' => 40],
            ['name' => 'Sage Green', 'label' => 'Sage Green', 'value' => 'Sage Green', 'type' => 'solid', 'hex' => '#9CAF88', 'is_active' => 1, 'sort_order' => 50],
        ];

        foreach ($colors as $row) {
            DB::table('colors')->updateOrInsert(
                ['name' => $row['name']],
                [
                    'label' => $row['label'],
                    'value' => $row['value'],
                    'type' => $row['type'],
                    'hex' => $row['hex'],
                    'is_active' => $row['is_active'],
                    'sort_order' => $row['sort_order'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}

