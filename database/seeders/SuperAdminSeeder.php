<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\School;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil sekolah default yang sudah dibuat oleh DefaultSchoolSeeder
        $defaultSchool = School::where('slug', 'default')->firstOrFail();

        // Buat akun super_admin (idempotent)
        User::updateOrCreate(
            ['email' => 'superadmin@eperpus.com'],
            [
                'name'               => 'Super Admin',
                'password'           => Hash::make('superadmin123'),
                'role'               => 'super_admin',
                'school_id'          => null,
                'email_verified_at'  => now(),
            ]
        );

        // Update akun admin existing: jadikan school_admin dengan school_id default
        User::where('email', 'admin@eperpus.com')->update([
            'role'               => 'school_admin',
            'school_id'          => $defaultSchool->id,
            'email_verified_at'  => now(),
        ]);

        $this->command->info("SuperAdminSeeder: super_admin dibuat, admin@eperpus.com diupdate ke school_admin (school_id={$defaultSchool->id}).");
    }
}
