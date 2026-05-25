<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('settings')->insert([
            'hospital_name' => 'Genesis Homeopathic Clinic',
            'email'         => 'info@genesishomeopathicclinic.com',
            'phone'         => '+1 (555) 000-0001',
            'address'       => '',
            'website'       => '',
            'currency'      => '$',
            'timezone'      => 'UTC',
            'date_format'   => 'd M Y',
            'per_page'      => 15,
            'footer_text'   => 'Genesis Homeopathic Clinic - Since 2016',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}
