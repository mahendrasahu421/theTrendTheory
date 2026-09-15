<?php

namespace Database\Seeders;

use App\Models\PincodeRule;
use Illuminate\Database\Seeder;

class PincodeRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rules = [
            [
                'pincode' => '110001',
                'city' => 'New Delhi',
                'state' => 'Delhi',
                'zone' => 'Metro',
                'delivery_days_min' => 2,
                'delivery_days_max' => 3,
                'is_serviceable' => true,
                'is_cod_allowed' => true,
                'is_return_allowed' => true,
                'is_exchange_only' => false,
                'risk_level' => 'low',
                'total_orders' => 45,
                'cod_orders' => 20,
                'returned_orders' => 2,
                'rto_orders' => 1,
                'return_rate' => 4.44,
                'rto_rate' => 2.22,
                'admin_notes' => 'Central Delhi Metro Hub — Priority dispatch.',
            ],
            [
                'pincode' => '400001',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'zone' => 'Metro',
                'delivery_days_min' => 2,
                'delivery_days_max' => 3,
                'is_serviceable' => true,
                'is_cod_allowed' => true,
                'is_return_allowed' => true,
                'is_exchange_only' => false,
                'risk_level' => 'low',
                'total_orders' => 60,
                'cod_orders' => 30,
                'returned_orders' => 3,
                'rto_orders' => 2,
                'return_rate' => 5.00,
                'rto_rate' => 3.33,
                'admin_notes' => 'Mumbai South Metro — Safe delivery cluster.',
            ],
            [
                'pincode' => '800001',
                'city' => 'Patna',
                'state' => 'Bihar',
                'zone' => 'Tier-1',
                'delivery_days_min' => 3,
                'delivery_days_max' => 5,
                'is_serviceable' => true,
                'is_cod_allowed' => false,
                'is_return_allowed' => true,
                'is_exchange_only' => false,
                'risk_level' => 'high',
                'total_orders' => 22,
                'cod_orders' => 16,
                'returned_orders' => 4,
                'rto_orders' => 8,
                'return_rate' => 18.18,
                'rto_rate' => 36.36,
                'admin_notes' => 'High COD cancellation rate (36%). COD blocked; prepaid orders only.',
            ],
            [
                'pincode' => '122001',
                'city' => 'Gurugram',
                'state' => 'Haryana',
                'zone' => 'Metro',
                'delivery_days_min' => 2,
                'delivery_days_max' => 3,
                'is_serviceable' => true,
                'is_cod_allowed' => true,
                'is_return_allowed' => false,
                'is_exchange_only' => true,
                'risk_level' => 'high',
                'total_orders' => 35,
                'cod_orders' => 15,
                'returned_orders' => 14,
                'rto_orders' => 2,
                'return_rate' => 40.00,
                'rto_rate' => 5.71,
                'admin_notes' => 'High return abuse rate (40%). Returns disabled; Exchange-Only policy enforced.',
            ],
            [
                'pincode' => '560001',
                'city' => 'Bengaluru',
                'state' => 'Karnataka',
                'zone' => 'Metro',
                'delivery_days_min' => 2,
                'delivery_days_max' => 4,
                'is_serviceable' => true,
                'is_cod_allowed' => true,
                'is_return_allowed' => true,
                'is_exchange_only' => false,
                'risk_level' => 'low',
                'total_orders' => 52,
                'cod_orders' => 22,
                'returned_orders' => 2,
                'rto_orders' => 1,
                'return_rate' => 3.85,
                'rto_rate' => 1.92,
                'admin_notes' => 'Bangalore Central — Express courier hub.',
            ],
        ];

        foreach ($rules as $r) {
            PincodeRule::updateOrCreate(['pincode' => $r['pincode']], $r);
        }
    }
}

