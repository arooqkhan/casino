<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure roles exist
        $superRole = Role::firstOrCreate(['name' => 'super']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole  = Role::firstOrCreate(['name' => 'user']);

        // Create default admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'otp' => '123456',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'dob' => '1990-01-01',
                'address' => '123 Main Street, New York',
                'email_verified_at' => now(),
                'is_verified' => true,
                'password' => Hash::make('admin@gmail.com'), // secure password
                'status' => 1,
                'matches' => 10,
                'total_wager' => 500,
                'largest_wager' => 200,
                'win' => 5,
                'total_earning' => 1000,
                'hold_amount' => 50,
                'balance' => 950,
                'firebase_token' => null,
                'code' => 'ABC123',
                'invited_code' => 'XYZ789',
                'verification_status' => true,
                'bonus_status' => true,
                'kyc_status' => false,
            ]
        );

        // Assign role
        $admin->assignRole($superRole);
    }
}
