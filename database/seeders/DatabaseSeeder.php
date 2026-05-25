<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SettingsSeeder::class,
            UserSeeder::class,
            PatientSeeder::class,
            DoctorSeeder::class,
            AppointmentSeeder::class,
            WardSeeder::class,
            MedicineSeeder::class,
            LabTestSeeder::class,
            InvoiceSeeder::class,
            StaffSeeder::class,
        ]);
    }
}
