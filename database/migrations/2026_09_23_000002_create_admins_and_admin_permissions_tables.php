<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create 'admins' table
        if (!Schema::hasTable('admins')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('phone', 20)->nullable();
                $table->string('password');
                $table->string('role')->default('admin')->index();
                $table->string('profile_image')->nullable();
                $table->boolean('is_active')->default(true)->index();
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // 2. Create 'admin_permissions' table
        if (!Schema::hasTable('admin_permissions')) {
            Schema::create('admin_permissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();
                $table->string('permission');
                $table->unique(['admin_id', 'permission']);
            });
        }

        // 3. Create 'admin_password_resets' table
        if (!Schema::hasTable('admin_password_resets')) {
            Schema::create('admin_password_resets', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        // 4. Add 'admin_id' to employees table if table exists and column doesn't
        if (Schema::hasTable('employees') && !Schema::hasColumn('employees', 'admin_id')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->foreignId('admin_id')->nullable()->after('user_id')->constrained('admins')->nullOnDelete();
            });
        }

        // 5. Drop foreign key constraint on notifications.created_by so it doesn't strictly require users.id
        if (Schema::hasTable('notifications')) {
            try {
                Schema::table('notifications', function (Blueprint $table) {
                    $table->dropForeign(['created_by']);
                });
            } catch (\Throwable $e) {
                // Ignore if constraint doesn't exist
            }
        }

        // 6. Migrate existing staff from `users` to `admins`
        if (Schema::hasTable('users')) {
            $staffRoles = ['super_admin', 'admin', 'hr', 'product_manager', 'product_editor', 'support_staff', 'staff'];
            $staffUsers = DB::table('users')->whereIn('role', $staffRoles)->get();

            foreach ($staffUsers as $u) {
                $existingAdmin = DB::table('admins')->where('email', $u->email)->first();
                if (!$existingAdmin) {
                    $adminId = DB::table('admins')->insertGetId([
                        'id'            => $u->id, // Preserve same ID if possible
                        'name'          => $u->name,
                        'email'         => $u->email,
                        'phone'         => $u->phone ?? null,
                        'password'      => $u->password,
                        'role'          => $u->role,
                        'profile_image' => $u->profile_image ?? null,
                        'is_active'     => $u->is_active ?? true,
                        'remember_token'=> $u->remember_token ?? null,
                        'created_at'    => $u->created_at ?? now(),
                        'updated_at'    => $u->updated_at ?? now(),
                    ]);
                } else {
                    $adminId = $existingAdmin->id;
                }

                // Copy permissions from user_permissions to admin_permissions
                if (Schema::hasTable('user_permissions')) {
                    $userPerms = DB::table('user_permissions')->where('user_id', $u->id)->get();
                    foreach ($userPerms as $perm) {
                        DB::table('admin_permissions')->updateOrInsert(
                            ['admin_id' => $adminId, 'permission' => $perm->permission],
                            ['admin_id' => $adminId, 'permission' => $perm->permission]
                        );
                    }
                }

                // If an employee record was linked to user_id, link it to admin_id
                if (Schema::hasTable('employees')) {
                    DB::table('employees')->where('user_id', $u->id)->update(['admin_id' => $adminId]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('employees') && Schema::hasColumn('employees', 'admin_id')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropForeign(['admin_id']);
                $table->dropColumn('admin_id');
            });
        }

        Schema::dropIfExists('admin_password_resets');
        Schema::dropIfExists('admin_permissions');
        Schema::dropIfExists('admins');
    }
};
