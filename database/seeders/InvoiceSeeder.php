<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\Patient;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::all();
        if ($patients->isEmpty()) return;

        $services = [
            ['Consultation Fee',              150.00, 'consultation'],
            ['X-Ray (Chest)',                  35.00, 'other'],
            ['Blood Test (CBC)',               15.00, 'lab_test'],
            ['Urine Analysis',                 10.00, 'lab_test'],
            ['ECG',                            30.00, 'other'],
            ['Ultrasound (Abdomen)',            60.00, 'other'],
            ['Medication (Amoxicillin)',        20.00, 'medicine'],
            ['Medication (Paracetamol)',         5.00, 'medicine'],
            ['Nursing Charges',                80.00, 'other'],
            ['Room Charges (1 day)',           100.00, 'bed'],
        ];

        $statuses = ['paid','paid','paid','sent','partially_paid'];

        for ($i = 0; $i < 20; $i++) {
            $patient  = $patients->random();
            $date     = now()->subDays(rand(1, 120));
            $numItems = rand(2, 5);
            $subtotal = 0;

            $invoice = Invoice::create([
                'patient_id'   => $patient->id,
                'invoice_date' => $date->format('Y-m-d'),
                'due_date'     => $date->copy()->addDays(30)->format('Y-m-d'),
                'status'       => 'draft',
                'subtotal'     => 0,
                'discount'     => 0,
                'tax'          => 0,
                'total_amount' => 0,
                'paid_amount'  => 0,
                'due_amount'   => 0,
                'notes'        => 'Invoice for medical services.',
            ]);

            $selectedIndexes = (array) array_rand($services, min($numItems, count($services)));
            foreach ($selectedIndexes as $idx) {
                [$desc, $price, $type] = $services[$idx];
                InvoiceItem::create([
                    'invoice_id'  => $invoice->id,
                    'description' => $desc,
                    'item_type'   => $type,
                    'quantity'    => 1,
                    'unit_price'  => $price,
                    'total'       => $price,
                ]);
                $subtotal += $price;
            }

            $status = $statuses[array_rand($statuses)];
            $paid   = 0;
            if ($status === 'paid')           $paid = $subtotal;
            if ($status === 'partially_paid') $paid = round($subtotal * (rand(30, 80) / 100), 2);

            $invoice->update([
                'subtotal'     => $subtotal,
                'total_amount' => $subtotal,
                'paid_amount'  => $paid,
                'due_amount'   => $subtotal - $paid,
                'status'       => $status,
            ]);

            if ($paid > 0) {
                Payment::create([
                    'invoice_id'     => $invoice->id,
                    'patient_id'     => $patient->id,
                    'amount'         => $paid,
                    'payment_date'   => $date->format('Y-m-d'),
                    'payment_method' => ['cash','card','bank_transfer'][rand(0,2)],
                    'notes'          => 'Payment received.',
                ]);
            }
        }
    }
}
