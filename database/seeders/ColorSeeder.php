<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        // Minimal default seed values for admin forms.
        $colors = [
            ['name' => 'Black', 'label' => 'Black', 'value' => 'Black', 'type' => 'solid', 'hex' => '#111111', 'is_active' => 1, 'sort_order' => 10],
            ['name' => 'White', 'label' => 'White', 'value' => 'White', 'type' => 'solid', 'hex' => '#FFFFFF', 'is_active' => 1, 'sort_order' => 20],
            ['name' => 'Navy', 'label' => 'Navy', 'value' => 'Navy', 'type' => 'solid', 'hex' => '#0F2747', 'is_active' => 1, 'sort_order' => 30],
            ['name' => 'Charcoal', 'label' => 'Charcoal', 'value' => 'Charcoal', 'type' => 'solid', 'hex' => '#3B3F46', 'is_active' => 1, 'sort_order' => 40],
            ['name' => 'Olive', 'label' => 'Olive', 'value' => 'Olive', 'type' => 'solid', 'hex' => '#556B2F', 'is_active' => 1, 'sort_order' => 50],
            ['name' => 'Burgundy', 'label' => 'Burgundy', 'value' => 'Burgundy', 'type' => 'solid', 'hex' => '#7A1F3D', 'is_active' => 1, 'sort_order' => 60],
            ['name' => 'Sky Blue', 'label' => 'Sky Blue', 'value' => 'Sky Blue', 'type' => 'solid', 'hex' => '#8EC5E8', 'is_active' => 1, 'sort_order' => 70],
            ['name' => 'Sage', 'label' => 'Sage', 'value' => 'Sage', 'type' => 'solid', 'hex' => '#9CAF88', 'is_active' => 1, 'sort_order' => 80],
            ['name' => 'Dusty Pink', 'label' => 'Dusty Pink', 'value' => 'Dusty Pink', 'type' => 'solid', 'hex' => '#D8A1A9', 'is_active' => 1, 'sort_order' => 90],
            ['name' => 'Beige', 'label' => 'Beige', 'value' => 'Beige', 'type' => 'solid', 'hex' => '#D9C7A3', 'is_active' => 1, 'sort_order' => 100],
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

