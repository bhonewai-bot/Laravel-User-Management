<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Roles
            $adminRoleId = DB::table('roles')->insertGetId(['name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
            $operatorRoleId = DB::table('roles')->insertGetId(['name' => 'operator', 'created_at' => now(), 'updated_at' => now()]);
            $cashierRoleId = DB::table('roles')->insertGetId(['name' => 'cashier', 'created_at' => now(), 'updated_at' => now()]);

            // Features
            $userFeatureId = DB::table('features')->insertGetId(['name' => 'user', 'created_at' => now(), 'updated_at' => now()]);
            $rolesFeatureId = DB::table('features')->insertGetId(['name' => 'roles', 'created_at' => now(), 'updated_at' => now()]);
            $productFeatureId = DB::table('features')->insertGetId(['name' => 'product', 'created_at' => now(), 'updated_at' => now()]);

            // Permissions (feature.action)
            $permIds = [];
            $insertPerm = function (string $action, int $featureId) use (&$permIds) {
                $id = DB::table('permissions')->insertGetId([
                    'name' => $action,
                    'feature_id' => $featureId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $permIds["{$featureId}.{$action}"] = $id;
            };

            foreach (['create','read','update','delete'] as $a) $insertPerm($a, $userFeatureId);
            foreach (['create','read','update','delete'] as $a) $insertPerm($a, $rolesFeatureId);
            foreach (['create','read','update','delete'] as $a) $insertPerm($a, $productFeatureId);

            // Assign permissions to roles (match your lab table)
            // Admin: everything
            $allPerms = DB::table('permissions')->pluck('id')->all();
            foreach ($allPerms as $pid) {
                DB::table('role_permissions')->insert([
                    'role_id' => $adminRoleId,
                    'permission_id' => $pid,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Operator example (adjust to your lab rule)
            // user: create/read/update, roles: read
            $operatorAllowed = [
                // user.*
                $permIds["{$userFeatureId}.create"],
                $permIds["{$userFeatureId}.read"],
                $permIds["{$userFeatureId}.update"],
                // roles.read
                $permIds["{$rolesFeatureId}.read"],
            ];
            foreach ($operatorAllowed as $pid) {
                DB::table('role_permissions')->insert([
                    'role_id' => $operatorRoleId,
                    'permission_id' => $pid,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Cashier example (adjust to your lab rule)
            // user.read only
            $cashierAllowed = [
                $permIds["{$userFeatureId}.read"],
            ];
            foreach ($cashierAllowed as $pid) {
                DB::table('role_permissions')->insert([
                    'role_id' => $cashierRoleId,
                    'permission_id' => $pid,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Admin user
            DB::table('admin_users')->insert([
                'name' => 'Admin',
                'username' => 'admin',
                'role_id' => $adminRoleId,
                'password' => Hash::make('admin123'),
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }
}
