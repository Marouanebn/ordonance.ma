<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Patient permissions
            'view_own_profile',
            'update_own_profile',
            'view_own_ordonnances',

            // Medecin permissions
            'view_medecin_profile',
            'update_medecin_profile',
            'view_patients',
            'create_ordonnance',
            'update_ordonnance',
            'delete_ordonnance',
            'view_ordonnances',

            // Pharmacien permissions
            'view_pharmacien_profile',
            'update_pharmacien_profile',
            'view_medicaments',
            'create_demande_laboratoire',
            'view_demandes_laboratoire',

            // Laboratoire permissions
            'view_laboratoire_profile',
            'update_laboratoire_profile',
            'view_laboratoire_demandes',
            'update_demande_status',

            // Admin permissions
            'view_all_users',
            'view_statistics',
            'manage_roles',
            'manage_permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $patientRole = Role::create(['name' => 'patient']);
        $patientRole->givePermissionTo([
            'view_own_profile',
            'update_own_profile',
            'view_own_ordonnances',
        ]);

        $medecinRole = Role::create(['name' => 'medecin']);
        $medecinRole->givePermissionTo([
            'view_medecin_profile',
            'update_medecin_profile',
            'view_patients',
            'create_ordonnance',
            'update_ordonnance',
            'delete_ordonnance',
            'view_ordonnances',
        ]);

        $pharmacienRole = Role::create(['name' => 'pharmacien']);
        $pharmacienRole->givePermissionTo([
            'view_pharmacien_profile',
            'update_pharmacien_profile',
            'view_medicaments',
            'create_demande_laboratoire',
            'view_demandes_laboratoire',
        ]);

        $laboratoireRole = Role::create(['name' => 'laboratoire']);
        $laboratoireRole->givePermissionTo([
            'view_laboratoire_profile',
            'update_laboratoire_profile',
            'view_laboratoire_demandes',
            'update_demande_status',
        ]);

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());
    }
}
