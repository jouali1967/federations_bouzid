<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions based on the sidebar configuration
        $permissions = [
            // Employés
            'personnes.index', 'personnes.create', 'personnes.edit',
            // Primes
            'primes.index', 'primes.create', 'primes.edit',
            // Sanctions
            'sanctions.index', 'sanctions.create', 'sanctions.edit', 'sanctions.pdf',
            // Gestion Salaires
            'salaires.gestion', 'salaires.impression',
            // Gestion Enfants
            'enfants.create',
            // Gestion Cnss
            'cnss.create',
            // Augmentations
            'augmentations.create',
            // Declarations
            'declarations.create', 
            'declarations.montants', // Sub-item from Declarations
            // Editions
            'editions.employes', 'editions.pdf', 'editions.declares',
            
            // Add any other permissions your application might need
            // For example, if 'manage.permissions' is a Spatie permission:
            'manage.permissions', 
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $this->command->info('Permissions created successfully.');

        // Create or find the admin role
        // Since you confirmed 'admin' role exists, firstOrCreate is safe and robust.
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        $this->command->info('Admin role found/created successfully.');

        // Assign all defined permissions to the admin role
        $adminRole->syncPermissions($permissions);

        $this->command->info('All defined permissions have been synced to the admin role.');

        // You mentioned you have an admin user. If you want to ensure this user has the admin role:
        // $adminUser = User::where('email', 'admin@example.com')->first(); // Adjust email or find by other unique identifier
        // if ($adminUser) {
        //     if (!$adminUser->hasRole('admin')) {
        //         $adminUser->assignRole('admin');
        //         $this->command->info('Admin role assigned to admin user.');
        //     }
        // } else {
        //     // Optionally, create the admin user if they don't exist
        //     // $user = User::create([
        //     //     'name' => 'Admin User',
        //     //     'email' => 'admin@example.com',
        //     //     'password' => Hash::make('password') // Change password
        //     // ]);
        //     // $user->assignRole($adminRole);
        //     // $this->command->info('Admin user created and assigned admin role.');
        // }

        // You mentioned 'role1' exists. If you need to assign specific permissions to it,
        // you would do it similarly:
        // $role1 = Role::where('name', 'role1')->first();
        // if ($role1) {
        //     $role1Permissions = [
        //         // specify permissions for role1, e.g.:
        //         // 'personnes.index', 
        //         // 'primes.index',
        //     ];
        //     $role1->syncPermissions($role1Permissions);
        //     $this->command->info('Permissions synced to role1.');
        // }
        
        $this->command->info('Roles and Permissions seeder finished.');
    }
}
