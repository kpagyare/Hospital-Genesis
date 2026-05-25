<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Patient;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $patients = [
            ['first_name'=>'John',    'last_name'=>'Smith',    'email'=>'john.smith@email.com',    'gender'=>'Male',   'dob'=>'1985-03-15','blood'=>'O+', 'phone'=>'555-1001','address'=>'12 Oak St'],
            ['first_name'=>'Mary',    'last_name'=>'Johnson',  'email'=>'mary.johnson@email.com',  'gender'=>'Female', 'dob'=>'1992-07-22','blood'=>'A+', 'phone'=>'555-1002','address'=>'34 Maple Ave'],
            ['first_name'=>'Robert',  'last_name'=>'Williams', 'email'=>'robert.w@email.com',      'gender'=>'Male',   'dob'=>'1978-11-08','blood'=>'B+', 'phone'=>'555-1003','address'=>'56 Pine Rd'],
            ['first_name'=>'Linda',   'last_name'=>'Brown',    'email'=>'linda.brown@email.com',   'gender'=>'Female', 'dob'=>'1989-04-30','blood'=>'AB+','phone'=>'555-1004','address'=>'78 Elm St'],
            ['first_name'=>'Michael', 'last_name'=>'Davis',    'email'=>'michael.d@email.com',     'gender'=>'Male',   'dob'=>'1995-09-14','blood'=>'A-', 'phone'=>'555-1005','address'=>'90 Cedar Ln'],
            ['first_name'=>'Patricia','last_name'=>'Miller',   'email'=>'patricia.m@email.com',    'gender'=>'Female', 'dob'=>'1960-12-25','blood'=>'O-', 'phone'=>'555-1006','address'=>'11 Birch Dr'],
            ['first_name'=>'James',   'last_name'=>'Wilson',   'email'=>'james.w@email.com',       'gender'=>'Male',   'dob'=>'1973-06-18','blood'=>'B-', 'phone'=>'555-1007','address'=>'22 Walnut Blvd'],
            ['first_name'=>'Barbara', 'last_name'=>'Moore',    'email'=>'barbara.m@email.com',     'gender'=>'Female', 'dob'=>'2001-02-07','blood'=>'AB-','phone'=>'555-1008','address'=>'33 Cherry Way'],
            ['first_name'=>'David',   'last_name'=>'Taylor',   'email'=>'david.t@email.com',       'gender'=>'Male',   'dob'=>'1988-08-19','blood'=>'O+', 'phone'=>'555-1009','address'=>'44 Spruce Ct'],
            ['first_name'=>'Susan',   'last_name'=>'Anderson', 'email'=>'susan.a@email.com',       'gender'=>'Female', 'dob'=>'1966-05-11','blood'=>'A+', 'phone'=>'555-1010','address'=>'55 Poplar St'],
            ['first_name'=>'Charles', 'last_name'=>'Thomas',   'email'=>'charles.t@email.com',     'gender'=>'Male',   'dob'=>'1950-01-28','blood'=>'B+', 'phone'=>'555-1011','address'=>'66 Willow Ave'],
            ['first_name'=>'Jessica', 'last_name'=>'Jackson',  'email'=>'jessica.j@email.com',     'gender'=>'Female', 'dob'=>'1997-10-03','blood'=>'O+', 'phone'=>'555-1012','address'=>'77 Ash Rd'],
        ];

        foreach ($patients as $data) {
            $user = User::create([
                'name'      => $data['first_name'].' '.$data['last_name'],
                'email'     => $data['email'],
                'role'      => 'patient',
                'password'  => Hash::make('patient123'),
                'phone'     => $data['phone'],
                'is_active' => true,
            ]);

            Patient::create([
                'user_id'          => $user->id,
                'first_name'       => $data['first_name'],
                'last_name'        => $data['last_name'],
                'email'            => $data['email'],
                'phone'            => $data['phone'],
                'gender'           => $data['gender'],
                'date_of_birth'    => $data['dob'],
                'blood_group'      => $data['blood'],
                'address'          => $data['address'],
                'status'           => 'active',
            ]);
        }
    }
}
