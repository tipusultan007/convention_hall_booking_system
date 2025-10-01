<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // -------------------------------------------------------------------
        // ---                       DEFINE PERMISSIONS                    ---
        // -------------------------------------------------------------------

        // Dashboard
        Permission::create(['name' => 'view dashboard']);

        // Bookings
        Permission::create(['name' => 'view bookings']);
        Permission::create(['name' => 'create bookings']);
        Permission::create(['name' => 'edit bookings']);
        Permission::create(['name' => 'delete bookings']);
        Permission::create(['name' => 'manage booking payments']);
        Permission::create(['name' => 'download booking receipts']);

        // Income
        Permission::create(['name' => 'view income']);
        Permission::create(['name' => 'create income']);
        Permission::create(['name' => 'edit income']);
        Permission::create(['name' => 'delete income']);
        Permission::create(['name' => 'manage income categories']);

        // Expenses
        Permission::create(['name' => 'view expenses']);
        Permission::create(['name' => 'create expenses']);
        Permission::create(['name' => 'edit expenses']);
        Permission::create(['name' => 'delete expenses']);
        Permission::create(['name' => 'manage expense categories']);

        // HR (Workers & Salaries)
        Permission::create(['name' => 'view workers']);
        Permission::create(['name' => 'create workers']);
        Permission::create(['name' => 'edit workers']);
        Permission::create(['name' => 'delete workers']);
        Permission::create(['name' => 'view salaries']);
        Permission::create(['name' => 'generate salaries']);
        Permission::create(['name' => 'manage salary payments']);

        // Liabilities
        Permission::create(['name' => 'view liabilities']);
        Permission::create(['name' => 'create liabilities']);
        Permission::create(['name' => 'edit liabilities']);
        Permission::create(['name' => 'delete liabilities']);
        Permission::create(['name' => 'manage liability repayments']);
        Permission::create(['name' => 'manage lenders']);

        // Reports & Ledger
        Permission::create(['name' => 'view reports']);
        Permission::create(['name' => 'view ledger']);

        Permission::create(['name' => 'manage users']);

        // User Management (for the future)
        // Permission::create(['name' => 'manage users']);

        // -------------------------------------------------------------------
        // ---                 DEFINE ROLES & ASSIGN PERMISSIONS           ---
        // -------------------------------------------------------------------

        // -- ROLE: MANAGER --
        // A day-to-day operator. Can manage bookings, expenses, income, and workers,
        // but has no access to sensitive salary or financial report data.
        $managerRole = Role::create(['name' => 'manager']);
        $managerRole->givePermissionTo([
            'view dashboard',
            'view bookings', 'create bookings', 'edit bookings', 'delete bookings', 'manage booking payments', 'download booking receipts',
            'view expenses', 'create expenses', 'edit expenses', 'delete expenses',
            'view income', 'create income', 'edit income', 'delete income',
            'view workers', 'create workers', 'edit workers', 'delete workers',
            'view liabilities', 'create liabilities', 'edit liabilities', 'delete liabilities', 'manage liability repayments',
        ]);

        // -- ROLE: ACCOUNTANT --
        // Can see everything and manage all financial transactions, but cannot
        // create or delete core operational records like Bookings or Workers.
        $accountantRole = Role::create(['name' => 'accountant']);
        $accountantRole->givePermissionTo([
            'view dashboard',
            'view bookings', 'manage booking payments', 'download booking receipts',
            'view income', 'create income', 'edit income', 'delete income', 'manage income categories',
            'view expenses', 'create expenses', 'edit expenses', 'delete expenses', 'manage expense categories',
            'view workers',
            'view salaries', 'generate salaries', 'manage salary payments',
            'view liabilities', 'manage liability repayments', 'manage lenders',
            'view reports',
            'view ledger',
        ]);

        $viewerRole = Role::create(['name' => 'viewer']);
        $viewerRole->givePermissionTo([
            'view dashboard',
            'view bookings',
            'download booking receipts',
            'view income',
            'view expenses',
            'view workers',
            'view salaries',
            'view liabilities',
            'view reports',
            'view ledger',
        ]);

        // -- ROLE: SUPER-ADMIN --
        // Has god-mode. This role bypasses all permission checks.
        $adminRole = Role::create(['name' => 'super-admin']);
        $adminRole->givePermissionTo(Permission::all());
        // The super-admin gets all permissions implicitly via the AuthServiceProvider.

        // -------------------------------------------------------------------
        // ---                 CREATE DEMO USERS                         ---
        // -------------------------------------------------------------------

        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@app.com',
        ]);
        $adminUser->assignRole($adminRole);

        $managerUser = User::factory()->create([
            'name' => 'Manager User',
            'email' => 'manager@app.com',
        ]);
        $managerUser->assignRole($managerRole);

        $accountantUser = User::factory()->create([
            'name' => 'Accountant User',
            'email' => 'accountant@app.com',
        ]);
        $accountantUser->assignRole($accountantRole);

        $viewerUser = User::factory()->create([
            'name' => 'Viewer User',
            'email' => 'viewer@app.com',
        ]);
        $viewerUser->assignRole($viewerRole);
    }
}
