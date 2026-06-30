<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'city',
        'state',
        'address',
        'pincode',
        'profile_image',
        'is_active',
    ];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // ── Relationships ──────────────────────────────────
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
    public function userPermissions()
    {
        return $this->hasMany(UserPermission::class);
    }

    // ── Role checks ────────────────────────────────────
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function isHR(): bool
    {
        return $this->role === 'hr';
    }
    public function isProductEditor(): bool
    {
        return in_array($this->role, ['product_manager', 'product_editor'], true);
    }
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }
    public function isStaff(): bool
    {
        return in_array($this->role, ['super_admin', 'admin', 'hr', 'product_manager', 'product_editor'], true);
    }

    // ── Permission check ───────────────────────────────
    public function hasPermission(string $permission): bool
    {
        // Super admin has all permissions
        if ($this->isSuperAdmin())
            return true;

        // Role-based default permissions
        $rolePermissions = $this->getRolePermissions();
        if (in_array($permission, $rolePermissions))
            return true;

        // Custom user-level permissions
        return $this->userPermissions()->where('permission', $permission)->exists();
    }

    // ── Default permissions per role ───────────────────
    public function getRolePermissions(): array
    {
        return match ($this->role) {
            'super_admin' => ['*'], // all

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
            ],

            'hr' => [
                'dashboard.view',
                'employees.view',
                'employees.create',
                'employees.edit',
                'employees.delete',
                'inventory.view',
            ],

            'product_manager', 'product_editor' => [
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

            default => [], // customer
        };
    }

    // ── Helpers ────────────────────────────────────────
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'hr' => 'HR',
            'product_manager' => 'Product Manager',
            'product_editor' => 'Product Editor',
            default => 'Customer',
        };
    }

    public function getRoleBadgeAttribute(): string
    {
        return match ($this->role) {
            'super_admin' => 'badge-a',
            'admin' => 'badge-b',
            'hr' => 'badge-g',
            'product_manager', 'product_editor' => 'badge-p',
            default => 'badge-gray',
        };
    }

    public function getAvatarAttribute(): string
    {
        return $this->profile_image
            ? asset('storage/' . $this->profile_image)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=00285a&color=fff&size=100';
    }
}
