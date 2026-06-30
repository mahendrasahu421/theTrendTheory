<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    private const PASSWORD = 'password';

    private array $users = [
        ['name' => 'Super Admin', 'email' => 'superadmin@thetrend.test', 'role' => 'super_admin', 'phone' => '9000000001'],
        ['name' => 'Admin', 'email' => 'admin@thetrend.test', 'role' => 'admin', 'phone' => '9000000002'],
        ['name' => 'Product Manager', 'email' => 'productmanager@thetrend.test', 'role' => 'product_manager', 'phone' => '9000000003'],
        ['name' => 'Customer', 'email' => 'customer@thetrend.test', 'role' => 'customer', 'phone' => '9000000004'],
    ];

    private array $permissions = [
        'super_admin' => ['*'],
        'admin' => [
            'dashboard.view',
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',
            'orders.view',
            'orders.update',
            'orders.cancel',
            'customers.view',
            'customers.block',
            'inventory.view',
            'inventory.update',
            'employees.view',
            'reports.sales',
            'reports.inventory',
            'reports.customers',
            'settings.view',
            'settings.edit',
            'media.upload',
            'media.delete',
            'coupons.view',
            'coupons.create',
            'coupons.edit',
            'coupons.delete',
            'reviews.view',
            'reviews.moderate',
        ],
        'product_manager' => [
            'dashboard.view',
            'products.view',
            'products.create',
            'products.edit',
            'categories.view',
            'inventory.view',
            'inventory.update',
            'media.upload',
            'reviews.view',
        ],
        'customer' => [
            'profile.view',
            'profile.edit',
            'orders.view_own',
            'wishlist.manage',
            'reviews.create',
        ],
    ];

    public function run(): void
    {
        foreach ($this->users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'password' => Hash::make(self::PASSWORD),
                    'role' => $data['role'],
                    'email_verified_at' => now(),
                    'is_active' => true,
                ]
            );

            UserPermission::where('user_id', $user->id)->delete();

            foreach ($this->permissions[$data['role']] as $permission) {
                UserPermission::create([
                    'user_id' => $user->id,
                    'permission' => $permission,
                ]);
            }
        }
    }
}
