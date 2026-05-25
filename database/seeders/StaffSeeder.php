<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Staff;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $staffData = [
            ['Emily',   'Chen',     'emily.chen@hms.com',    'nurse',        'Nursing',        'Head Nurse',         55000, 'active'],
            ['Michael', 'Brown',    'michael.b@hms.com',     'receptionist', 'Administration', 'Senior Receptionist',42000, 'active'],
            ['Linda',   'Davis',    'linda.d@hms.com',       'pharmacist',   'Pharmacy',       'Chief Pharmacist',   60000, 'active'],
            ['James',   'Wilson',   'james.w2@hms.com',      'lab_staff',    'Laboratory',     'Lab Technician',     48000, 'active'],
            ['Patricia','Moore',    'patricia.m2@hms.com',   'accountant',   'Finance',        'Senior Accountant',  52000, 'active'],
            ['Kevin',   'Anderson', 'kevin.a@hms.com',       'nurse',        'Nursing',        'Staff Nurse',        45000, 'active'],
            ['Sandra',  'Thomas',   'sandra.t@hms.com',      'nurse',        'Emergency',      'Emergency Nurse',    47000, 'active'],
            ['George',  'Jackson',  'george.j@hms.com',      'receptionist', 'Administration', 'Receptionist',       38000, 'active'],
            ['Helen',   'White',    'helen.w@hms.com',       'lab_staff',    'Laboratory',     'Lab Analyst',        44000, 'active'],
            ['Frank',   'Harris',   'frank.h@hms.com',       'pharmacist',   'Pharmacy',       'Pharmacist',         50000, 'active'],
        ];

        foreach ($staffData as [$first, $last, $email, $role, $dept, $position, $salary, $status]) {
            $user = User::create([
                'name'      => $first.' '.$last,
                'email'     => $email,
                'role'      => $role,
                'password'  => Hash::make('staff123'),
                'is_active' => true,
            ]);

            Staff::create([
                'user_id'    => $user->id,
                'first_name' => $first,
                'last_name'  => $last,
                'email'      => $email,
                'department' => $dept,
                'position'   => $position,
                'salary'     => $salary,
                'join_date'  => now()->subMonths(rand(3, 36))->format('Y-m-d'),
                'status'     => $status,
            ]);
        }
    }
}
