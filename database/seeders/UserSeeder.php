<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Admin User',       'email' => 'admin@hms.com',       'role' => 'super_admin',  'password' => 'admin123'],
            ['name' => 'Dr. Sarah Johnson','email' => 'doctor@hms.com',      'role' => 'doctor',       'password' => 'doctor123'],
            ['name' => 'Emily Chen',       'email' => 'nurse@hms.com',       'role' => 'nurse',        'password' => 'nurse123'],
            ['name' => 'Michael Brown',    'email' => 'reception@hms.com',   'role' => 'receptionist', 'password' => 'reception123'],
            ['name' => 'Linda Davis',      'email' => 'pharmacy@hms.com',    'role' => 'pharmacist',   'password' => 'pharmacy123'],
            ['name' => 'James Wilson',     'email' => 'lab@hms.com',         'role' => 'lab_staff',    'password' => 'lab123'],
            ['name' => 'Patricia Moore',   'email' => 'accounts@hms.com',    'role' => 'accountant',   'password' => 'accounts123'],
            ['name' => 'John Patient',     'email' => 'patient@hms.com',     'role' => 'patient',      'password' => 'patient123'],
        ];

        foreach ($users as $data) {
            User::create([
                'name'      => $data['name'],
                'email'     => $data['email'],
                'role'      => $data['role'],
                'password'  => Hash::make($data['password']),
                'is_active' => true,
            ]);
        }
    }
}
