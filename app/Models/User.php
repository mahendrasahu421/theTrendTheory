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
        'google_id',
        'facebook_id',
        'social_avatar',
        'firebase_uid',
        'is_active',
    ];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * Mutator to ensure user name has the first letter of each word capitalized.
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = !empty($value) ? mb_convert_case(trim($value), MB_CASE_TITLE, "UTF-8") : $value;
    }

    public function setCityAttribute($value)
    {
        $this->attributes['city'] = !empty($value) ? mb_convert_case(trim($value), MB_CASE_TITLE, "UTF-8") : $value;
    }

    public function setStateAttribute($value)
    {
        $this->attributes['state'] = !empty($value) ? mb_convert_case(trim($value), MB_CASE_TITLE, "UTF-8") : $value;
    }

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
    public function refundAccounts()
    {
        return $this->hasMany(UserRefundAccount::class);
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
        return in_array($this->role, ['super_admin', 'admin', 'hr', 'product_manager', 'product_editor', 'support_staff', 'staff'], true);
    }

    // ── Permission check ───────────────────────────────
    public function hasPermission(string $permission): bool
    {
        // Super Admin & Admin have 100% full unrestricted access to everything
        if ($this->isSuperAdmin() || $this->isAdmin() || in_array($this->role, ['super_admin', 'admin'], true)) {
            return true;
        }

        // Custom assigned permissions for this specific staff user
        if ($this->userPermissions()->where('permission', $permission)->exists()) {
            return true;
        }

        // Role-based default permissions
        $rolePermissions = $this->getRolePermissions();
        if (in_array('*', $rolePermissions, true) || in_array($permission, $rolePermissions, true)) {
            return true;
        }

        return false;
    }

    // ── Sync Permissions for this user ─────────────────
    public function syncPermissions(array $permissions): void
    {
        $this->userPermissions()->delete();

        $rows = [];
        foreach (array_unique($permissions) as $perm) {
            if (!empty($perm)) {
                $rows[] = [
                    'user_id'    => $this->id,
                    'permission' => trim($perm),
                ];
            }
        }

        if (!empty($rows)) {
            \App\Models\UserPermission::insert($rows);
        }
    }

    // ── Defined Permission Categories & Modules ─────────
    public static function getDefinedPermissionGroups(): array
    {
        return [
            'catalog' => [
                'title' => 'Catalog & Inventory',
                'icon'  => 'bi-box-seam',
                'color' => '#4f46e5',
                'permissions' => [
                    'products.view'     => ['label' => 'View Products', 'desc' => 'Browse and search catalog products'],
                    'products.create'   => ['label' => 'Create Products', 'desc' => 'Add new streetwear pieces and drops'],
                    'products.edit'     => ['label' => 'Edit Products', 'desc' => 'Modify prices, descriptions, images, tags'],
                    'products.delete'   => ['label' => 'Delete Products', 'desc' => 'Remove products from catalog'],
                    'categories.view'   => ['label' => 'View Categories', 'desc' => 'Browse collection categories'],
                    'categories.create' => ['label' => 'Create Categories', 'desc' => 'Add new product categories'],
                    'categories.edit'   => ['label' => 'Edit Categories', 'desc' => 'Rename and update categories'],
                    'categories.delete' => ['label' => 'Delete Categories', 'desc' => 'Delete categories'],
                    'inventory.view'    => ['label' => 'View Inventory', 'desc' => 'Check stock levels and size variants'],
                    'inventory.update'  => ['label' => 'Update Stock', 'desc' => 'Adjust size quantities and stock alerts'],
                    'media.upload'      => ['label' => 'Upload Media', 'desc' => 'Upload gallery & lookbook media'],
                    'media.delete'      => ['label' => 'Delete Media', 'desc' => 'Remove media assets'],
                    'reviews.view'      => ['label' => 'View Reviews', 'desc' => 'Read customer reviews & ratings'],
                    'reviews.manage'    => ['label' => 'Moderate Reviews', 'desc' => 'Approve or delete reviews'],
                ],
            ],

            'orders_customers' => [
                'title' => 'Orders & Customer Management',
                'icon'  => 'bi-cart-check',
                'color' => '#d97706',
                'permissions' => [
                    'orders.view'       => ['label' => 'View Orders', 'desc' => 'See customer orders and invoices'],
                    'orders.update'     => ['label' => 'Update Orders', 'desc' => 'Mark as Confirmed, Shipped, Delivered'],
                    'orders.cancel'     => ['label' => 'Cancel & Refund', 'desc' => 'Cancel orders and process returns'],
                    'customers.view'    => ['label' => 'View Customers', 'desc' => 'Inspect customer profiles & history'],
                    'customers.block'   => ['label' => 'Block Customers', 'desc' => 'Block fraudulent user accounts'],
                    'coupons.view'      => ['label' => 'View Coupons', 'desc' => 'Browse discount codes & promos'],
                    'coupons.manage'    => ['label' => 'Manage Coupons', 'desc' => 'Create, edit & expire discount coupons'],
                ],
            ],

            'marketing_intelligence' => [
                'title' => 'Marketing, Automation & Intelligence',
                'icon'  => 'bi-lightning-charge',
                'color' => '#e11d48',
                'permissions' => [
                    'notifications.view'     => ['label' => 'View Notifications', 'desc' => 'Access notification delivery logs'],
                    'notifications.send'     => ['label' => 'Broadcast & Automations', 'desc' => 'Send push broadcasts & configure cart recovery triggers'],
                    'analytics.view'         => ['label' => 'Visitor Traffic & Geo', 'desc' => 'Inspect real-time visitors, origin & phone models'],
                    'analytics.activities'   => ['label' => 'Live Activity & Outreach', 'desc' => 'Live action stream with WhatsApp & Email outreach'],
                ],
            ],

            'hr_staff' => [
                'title' => 'Staff & Role Administration',
                'icon'  => 'bi-people',
                'color' => '#7e22ce',
                'permissions' => [
                    'employees.view'    => ['label' => 'View Staff Members', 'desc' => 'Browse staff directory'],
                    'employees.manage'  => ['label' => 'Create & Edit Staff', 'desc' => 'Add new staff and manage account status'],
                    'roles.manage'      => ['label' => 'Assign Permissions', 'desc' => 'Grant or revoke granular system permissions'],
                ],
            ],

            'reports_settings' => [
                'title' => 'Reports, SEO & System Settings',
                'icon'  => 'bi-sliders',
                'color' => '#059669',
                'permissions' => [
                    'reports.view'      => ['label' => 'Financial Reports', 'desc' => 'View sales, revenue, and order analytics'],
                    'settings.view'     => ['label' => 'View Settings', 'desc' => 'Inspect store information and SEO configurations'],
                    'settings.edit'     => ['label' => 'Modify Settings', 'desc' => 'Update store contact, banner & payment gateways'],
                ],
            ],
        ];
    }

    // ── Default permissions per role ───────────────────
    public function getRolePermissions(): array
    {
        return match ($this->role) {
            'super_admin', 'admin' => ['*'], // Full unrestricted access

            'hr' => [
                'dashboard.view',
                'employees.view',
                'employees.manage',
                'roles.manage',
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

            'support_staff' => [
                'dashboard.view',
                'orders.view',
                'orders.update',
                'customers.view',
                'reviews.view',
                'analytics.activities',
            ],

            default => [], // customer or custom staff
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
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }
        if ($this->social_avatar) {
            return $this->social_avatar;
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=00285a&color=fff&size=100';
    }
}
