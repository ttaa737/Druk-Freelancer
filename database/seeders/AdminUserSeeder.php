<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Main admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@drukfreelancer.bt'],
            [
                'name'                => 'Druk Freelancer Admin',
                'password'            => Hash::make('Admin@Druk2025!'),
                'email_verified_at'   => now(),
                'verification_status' => 'verified',
                'status'              => 'active',
                'preferred_language'  => 'en',
                'role'                => 'admin',
            ]
        );

        $admin->assignRole('admin');

        $admin->profile()->firstOrCreate(
            ['user_id' => $admin->id],
            [
                'bio'      => 'Platform administrator for Druk Freelancer.',
                'dzongkhag' => 'Thimphu',
            ]
        );

        $admin->wallet()->firstOrCreate(
            ['user_id' => $admin->id],
            [
                'available_balance' => 0,
                'escrow_balance'    => 0,
                'total_earned'      => 0,
                'total_spent'   => 0,
            ]
        );

        // Demo job poster
        $poster = User::firstOrCreate(
            ['email' => 'poster@demo.bt'],
            [
                'name'                => 'Sonam Dorji',
                'password'            => Hash::make('Demo@1234'),
                'email_verified_at'   => now(),
                'verification_status' => 'verified',
                'status'              => 'active',
                'phone'               => '+975-17100001',
                'role'                => 'job_poster',
            ]
        );
        $poster->assignRole('job_poster');
        $poster->profile()->firstOrCreate(['user_id' => $poster->id], ['dzongkhag' => 'Paro', 'company_name' => 'Bhutan Tech Pvt. Ltd.']);
        $poster->wallet()->firstOrCreate(['user_id' => $poster->id], ['available_balance' => 10000]);

        // Demo freelancer
        $freelancer = User::firstOrCreate(
            ['email' => 'freelancer@demo.bt'],
            [
                'name'                => 'Kinley Wangchuk',
                'password'            => Hash::make('Demo@1234'),
                'email_verified_at'   => now(),
                'verification_status' => 'verified',
                'status'              => 'active',
                'phone'               => '+975-17100002',
                'role'                => 'freelancer',
            ]
        );
        $freelancer->assignRole('freelancer');
        $freelancer->profile()->firstOrCreate(['user_id' => $freelancer->id], ['dzongkhag' => 'Thimphu', 'headline' => 'Full-Stack Web Developer', 'hourly_rate' => 500, 'availability' => 'available', 'experience_years' => 5]);
        $freelancer->wallet()->firstOrCreate(['user_id' => $freelancer->id], ['available_balance' => 2500]);

        $this->command->info('Admin and demo users seeded successfully.');
        $this->command->line('  Admin:      admin@drukfreelancer.bt / Admin@Druk2025!');
        $this->command->line('  Poster:     poster@demo.bt / Demo@1234');
        $this->command->line('  Freelancer: freelancer@demo.bt / Demo@1234');
    }
}
