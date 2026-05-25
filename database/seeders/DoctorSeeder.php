<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Doctor;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = [
            ['first'=>'Sarah',   'last'=>'Johnson',  'email'=>'sarah.j@hms.com',   'spec'=>'Cardiology',      'qual'=>'MD, FACC',        'fee'=>150, 'days'=>['Monday','Wednesday','Friday']],
            ['first'=>'William', 'last'=>'Martinez', 'email'=>'william.m@hms.com',  'spec'=>'Neurology',       'qual'=>'MD, PhD',          'fee'=>180, 'days'=>['Tuesday','Thursday','Saturday']],
            ['first'=>'Jennifer','last'=>'Garcia',   'email'=>'jennifer.g@hms.com', 'spec'=>'Pediatrics',      'qual'=>'MD, FAAP',         'fee'=>120, 'days'=>['Monday','Tuesday','Wednesday','Thursday','Friday']],
            ['first'=>'Richard', 'last'=>'Lee',      'email'=>'richard.l@hms.com',  'spec'=>'Orthopedics',     'qual'=>'MD, FAAOS',        'fee'=>160, 'days'=>['Monday','Wednesday','Friday']],
            ['first'=>'Amanda',  'last'=>'Harris',   'email'=>'amanda.h@hms.com',   'spec'=>'Gynecology',      'qual'=>'MD, FACOG',        'fee'=>140, 'days'=>['Tuesday','Thursday','Saturday']],
            ['first'=>'Thomas',  'last'=>'Clark',    'email'=>'thomas.c@hms.com',   'spec'=>'General Surgery', 'qual'=>'MD, FACS',         'fee'=>200, 'days'=>['Monday','Tuesday','Wednesday','Thursday']],
            ['first'=>'Karen',   'last'=>'Lewis',    'email'=>'karen.l@hms.com',    'spec'=>'Dermatology',     'qual'=>'MD, FAAD',         'fee'=>110, 'days'=>['Wednesday','Friday']],
            ['first'=>'Edward',  'last'=>'Young',    'email'=>'edward.y@hms.com',   'spec'=>'Ophthalmology',   'qual'=>'MD, FACS',         'fee'=>130, 'days'=>['Monday','Thursday']],
        ];

        foreach ($doctors as $d) {
            $user = User::create([
                'name'      => 'Dr. '.$d['first'].' '.$d['last'],
                'email'     => $d['email'],
                'role'      => 'doctor',
                'password'  => Hash::make('doctor123'),
                'is_active' => true,
            ]);

            Doctor::create([
                'user_id'        => $user->id,
                'first_name'     => $d['first'],
                'last_name'      => $d['last'],
                'email'          => $d['email'],
                'specialization' => $d['spec'],
                'qualification'  => $d['qual'],
                'consultation_fee' => $d['fee'],
                'available_days' => $d['days'],
                'status'         => 'active',
            ]);
        }
    }
}
