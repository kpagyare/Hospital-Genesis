<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicineCategory;
use App\Models\Medicine;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Antibiotics'       => [
                ['Amoxicillin 500mg','Amoxicillin','Capsule',0.50,2.50,200,50,'2027-06-30'],
                ['Azithromycin 250mg','Azithromycin','Tablet',0.80,3.00,150,30,'2026-12-31'],
                ['Ciprofloxacin 500mg','Ciprofloxacin','Tablet',0.60,2.80,180,40,'2027-03-31'],
            ],
            'Analgesics'        => [
                ['Paracetamol 500mg','Paracetamol','Tablet',0.05,0.25,500,100,'2028-01-31'],
                ['Ibuprofen 400mg','Ibuprofen','Tablet',0.10,0.50,400,80,'2027-09-30'],
                ['Aspirin 75mg','Aspirin','Tablet',0.08,0.30,600,100,'2028-06-30'],
            ],
            'Antihypertensives' => [
                ['Amlodipine 5mg','Amlodipine','Tablet',0.15,0.75,300,60,'2027-12-31'],
                ['Lisinopril 10mg','Lisinopril','Tablet',0.20,1.00,250,50,'2027-08-31'],
                ['Metoprolol 50mg','Metoprolol','Tablet',0.18,0.90,280,60,'2027-11-30'],
            ],
            'Antidiabetics'     => [
                ['Metformin 500mg','Metformin','Tablet',0.12,0.60,350,70,'2027-10-31'],
                ['Glibenclamide 5mg','Glibenclamide','Tablet',0.15,0.70,200,40,'2027-07-31'],
                ['Insulin Regular 10mL','Insulin Regular','Injection',8.00,25.00,60,15,'2026-09-30'],
            ],
            'Vitamins'          => [
                ['Vitamin C 1000mg','Vitamin C','Tablet',0.20,1.00,400,80,'2028-12-31'],
                ['Vitamin D3 1000IU','Vitamin D3','Capsule',0.25,1.20,300,60,'2028-06-30'],
                ['Folic Acid 5mg','Folic Acid','Tablet',0.08,0.35,500,100,'2028-09-30'],
            ],
            'Antihistamines'    => [
                ['Cetirizine 10mg','Cetirizine','Tablet',0.12,0.60,300,60,'2027-11-30'],
                ['Loratadine 10mg','Loratadine','Tablet',0.15,0.70,250,50,'2027-08-31'],
            ],
            'Antacids'          => [
                ['Omeprazole 20mg','Omeprazole','Capsule',0.20,1.00,280,60,'2027-12-31'],
                ['Antacid Suspension 200mL','Antacid','Syrup',1.50,5.00,100,20,'2026-12-31'],
            ],
        ];

        foreach ($categories as $catName => $meds) {
            $cat = MedicineCategory::create(['name' => $catName]);

            foreach ($meds as [$name, $generic, $type, $purchase, $selling, $stock, $alert, $expiry]) {
                Medicine::create([
                    'category_id'     => $cat->id,
                    'name'            => $name,
                    'generic_name'    => $generic,
                    'type'            => $type,
                    'purchase_price'  => $purchase,
                    'selling_price'   => $selling,
                    'stock_quantity'  => $stock,
                    'low_stock_alert' => $alert,
                    'expiry_date'     => $expiry,
                    'status'          => 'active',
                ]);
            }
        }
    }
}
