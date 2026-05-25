<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LabTestCategory;
use App\Models\LabTest;

class LabTestSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Hematology' => [
                ['Complete Blood Count','CBC','Full blood cell count and differential',15.00,'10-80 x10^9/L','cells/L'],
                ['Hemoglobin','HGB','Hemoglobin level in blood',8.00,'12-17 g/dL','g/dL'],
                ['Blood Group & Rh','BGT','ABO blood grouping and Rh typing',10.00,'A/B/AB/O, +/-','—'],
                ['ESR','ESR','Erythrocyte sedimentation rate',12.00,'0-20 mm/hr','mm/hr'],
            ],
            'Biochemistry' => [
                ['Fasting Blood Sugar','FBS','Blood glucose measurement fasting',10.00,'70-100 mg/dL','mg/dL'],
                ['HbA1c','HBA','Glycated hemoglobin 3-month average',25.00,'< 5.7 %','%'],
                ['Lipid Profile','LIP','Cholesterol, LDL, HDL, triglycerides',30.00,'Total < 200 mg/dL','mg/dL'],
                ['Kidney Function Test','KFT','Urea, creatinine, electrolytes',35.00,'Creatinine 0.6-1.2 mg/dL','mg/dL'],
                ['Liver Function Test','LFT','ALT, AST, ALP, bilirubin',40.00,'ALT 7-56 U/L','U/L'],
                ['Thyroid Profile','THY','TSH, Free T3, Free T4',45.00,'TSH 0.4-4.0 mIU/L','mIU/L'],
            ],
            'Microbiology' => [
                ['Urine Culture & Sensitivity','UCS','Detect urinary tract pathogens',30.00,'No growth','—'],
                ['Blood Culture','BLC','Detect bloodstream infection',40.00,'No growth','—'],
                ['Stool Culture','STC','Detect enteric pathogens',25.00,'No pathogenic growth','—'],
                ['Malaria Antigen Test','MAL','Rapid malaria detection',15.00,'Negative','—'],
            ],
            'Serology' => [
                ['HIV 1 & 2 Antibody','HIV','HIV screening test',20.00,'Non-reactive','—'],
                ['Hepatitis B Antigen','HBS','HBsAg hepatitis B screening',20.00,'Non-reactive','—'],
                ['Hepatitis C Antibody','HCV','Anti-HCV hepatitis C screening',20.00,'Non-reactive','—'],
                ['Widal Test','WID','Typhoid fever serology',15.00,'< 1:80 titre','titre'],
                ['Pregnancy Test (urine)','UPT','Human chorionic gonadotropin',8.00,'Negative','—'],
                ['C-Reactive Protein','CRP','Acute inflammation marker',18.00,'< 1.0 mg/dL','mg/dL'],
            ],
            'Urine Analysis' => [
                ['Urinalysis Routine','URN','Complete urine examination',10.00,'Normal','—'],
                ['Urine Protein 24hr','UPR','24-hour urine protein',20.00,'< 150 mg/24hr','mg/24hr'],
                ['Urine Microalbumin','UMA','Early kidney disease marker',25.00,'< 30 mg/L','mg/L'],
            ],
            'Radiology' => [
                ['Chest X-Ray','CXR','PA view chest radiograph',35.00,'Normal','—'],
                ['X-Ray Per View','XRY','Standard radiograph any view',25.00,'Normal','—'],
                ['Ultrasound Abdomen','USG','Abdominal ultrasound scan',60.00,'Normal','—'],
                ['ECG 12-lead','ECG','Electrocardiogram resting',30.00,'Normal sinus rhythm','—'],
            ],
        ];

        foreach ($categories as $catName => $tests) {
            $cat = LabTestCategory::create(['name' => $catName]);

            foreach ($tests as [$name, $code, $desc, $price, $normal, $unit]) {
                LabTest::create([
                    'category_id'  => $cat->id,
                    'name'         => $name,
                    'test_code'    => $code,
                    'description'  => $desc,
                    'price'        => $price,
                    'normal_range' => $normal,
                    'unit'         => $unit,
                    'status'       => 'active',
                ]);
            }
        }
    }
}
