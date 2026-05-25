<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ward;
use App\Models\Bed;

class WardSeeder extends Seeder
{
    public function run(): void
    {
        $wards = [
            ['name'=>'General Ward A',   'type'=>'General',   'charge'=>50,  'beds'=>10,'desc'=>'General purpose ward for common ailments'],
            ['name'=>'General Ward B',   'type'=>'General',   'charge'=>50,  'beds'=>10,'desc'=>'General purpose ward for common ailments'],
            ['name'=>'ICU',              'type'=>'ICU',        'charge'=>300, 'beds'=>6, 'desc'=>'Intensive Care Unit for critical patients'],
            ['name'=>'Maternity Ward',   'type'=>'Maternity',  'charge'=>100, 'beds'=>8, 'desc'=>'Ward for maternity and post-natal care'],
            ['name'=>'Pediatric Ward',   'type'=>'Pediatric',  'charge'=>80,  'beds'=>8, 'desc'=>'Ward for pediatric patients (children)'],
            ['name'=>'Surgical Ward',    'type'=>'Surgical',   'charge'=>150, 'beds'=>8, 'desc'=>'Post-operative recovery ward'],
            ['name'=>'Emergency Ward',   'type'=>'Emergency',  'charge'=>200, 'beds'=>6, 'desc'=>'Emergency and trauma care unit'],
            ['name'=>'Isolation Ward',   'type'=>'Isolation',  'charge'=>200, 'beds'=>4, 'desc'=>'Isolation ward for infectious disease patients'],
        ];

        foreach ($wards as $w) {
            $ward = Ward::create([
                'name'              => $w['name'],
                'ward_type'         => $w['type'],
                'bed_charge_per_day'=> $w['charge'],
                'description'       => $w['desc'],
                'status'            => 'active',
            ]);

            for ($i = 1; $i <= $w['beds']; $i++) {
                Bed::create([
                    'ward_id'        => $ward->id,
                    'bed_number'     => $ward->id . sprintf('%02d', $i),
                    'charge_per_day' => $w['charge'],
                    'status'         => 'available',
                ]);
            }
        }
    }
}
