<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::all();
        $doctors  = Doctor::all();

        if ($patients->isEmpty() || $doctors->isEmpty()) return;

        $statuses = ['confirmed','completed','completed','completed','cancelled','no_show'];
        $types    = ['regular','follow_up','emergency'];

        for ($i = 0; $i < 30; $i++) {
            $patient = $patients->random();
            $doctor  = $doctors->random();
            $date    = now()->subDays(rand(0, 90))->addDays(rand(-10, 10));
            $status  = $statuses[array_rand($statuses)];

            if ($date->isFuture()) $status = 'pending';

            Appointment::create([
                'patient_id'       => $patient->id,
                'doctor_id'        => $doctor->id,
                'appointment_date' => $date->format('Y-m-d'),
                'appointment_time' => sprintf('%02d:%02d', rand(8,17), [0,15,30,45][array_rand([0,15,30,45])]),
                'type'             => $types[array_rand($types)],
                'status'           => $status,
                'reason'           => 'Routine checkup and consultation',
                'fee'              => $doctor->consultation_fee,
                'notes'            => $status === 'completed' ? 'Patient examined. Prescribed medication.' : null,
            ]);
        }
    }
}
