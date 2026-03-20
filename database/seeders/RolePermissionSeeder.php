<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions
        $permissions = [
            // Jobs
            'jobs.view', 'jobs.create', 'jobs.edit', 'jobs.delete', 'jobs.feature',
            // Proposals
            'proposals.submit', 'proposals.view', 'proposals.award', 'proposals.reject',
            // Contracts
            'contracts.view', 'contracts.fund', 'contracts.sign', 'contracts.cancel',
            // Milestones
            'milestones.submit', 'milestones.approve', 'milestones.revision',
            // Wallet
            'wallet.view', 'wallet.deposit', 'wallet.withdraw',
            // Disputes
            'disputes.raise', 'disputes.view', 'disputes.resolve',
            // Reviews
            'reviews.create', 'reviews.view',
            // Admin
            'admin.dashboard', 'admin.users', 'admin.jobs', 'admin.disputes',
            'admin.verifications', 'admin.categories', 'admin.transactions',
            // Profile
            'profile.edit', 'profile.verify',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // ── Roles and their permissions ──────────────────────────────────────

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all()); // admin gets everything

        $posterRole = Role::firstOrCreate(['name' => 'job_poster', 'guard_name' => 'web']);
        $posterRole->syncPermissions([
            'jobs.view', 'jobs.create', 'jobs.edit', 'jobs.delete',
            'proposals.view', 'proposals.award', 'proposals.reject',
            'contracts.view', 'contracts.fund', 'contracts.sign', 'contracts.cancel',
            'milestones.approve', 'milestones.revision',
            'wallet.view', 'wallet.deposit', 'wallet.withdraw',
            'disputes.raise', 'disputes.view',
            'reviews.create', 'reviews.view',
            'profile.edit',
        ]);

        $freelancerRole = Role::firstOrCreate(['name' => 'freelancer', 'guard_name' => 'web']);
        $freelancerRole->syncPermissions([
            'jobs.view',
            'proposals.submit', 'proposals.view',
            'contracts.view', 'contracts.sign',
            'milestones.submit',
            'wallet.view', 'wallet.deposit', 'wallet.withdraw',
            'disputes.raise', 'disputes.view',
            'reviews.create', 'reviews.view',
            'profile.edit', 'profile.verify',
        ]);

        $this->command->info('Roles and permissions seeded successfully.');
    }
}
