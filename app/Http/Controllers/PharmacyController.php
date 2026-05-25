<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\MedicineSale;
use App\Models\MedicineSaleItem;
use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicine::with('category');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('medicine_id', 'like', "%{$request->search}%")
                  ->orWhere('generic_name', 'like', "%{$request->search}%");
            });
        }

        if ($request->category_id) $query->where('category_id', $request->category_id);
        if ($request->status)      $query->where('status', $request->status);

        $medicines   = $query->paginate(15);
        $categories  = MedicineCategory::all();
        $lowStock    = Medicine::whereRaw('stock_quantity <= low_stock_alert')->count();
        $outOfStock  = Medicine::where('stock_quantity', 0)->count();

        return view('pharmacy.index', compact('medicines', 'categories', 'lowStock', 'outOfStock'));
    }

    public function create()
    {
        $categories = MedicineCategory::all();
        return view('pharmacy.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:200',
            'category_id'      => 'nullable|exists:medicine_categories,id',
            'generic_name'     => 'nullable|string|max:200',
            'brand'            => 'nullable|string|max:200',
            'type'             => 'nullable|string|max:50',
            'unit'             => 'required|string|max:20',
            'purchase_price'   => 'required|numeric|min:0',
            'selling_price'    => 'required|numeric|min:0',
            'stock_quantity'   => 'required|integer|min:0',
            'low_stock_alert'  => 'required|integer|min:0',
            'expiry_date'      => 'nullable|date',
            'manufacturer'     => 'nullable|string|max:200',
            'description'      => 'nullable|string',
        ]);

        $medicine = Medicine::create($validated);
        ActivityLog::log('create', 'Pharmacy', "Added medicine: {$medicine->name}");

        return redirect()->route('pharmacy.index')->with('success', "Medicine '{$medicine->name}' added successfully.");
    }

    public function edit(Medicine $medicine)
    {
        $categories = MedicineCategory::all();
        return view('pharmacy.edit', compact('medicine', 'categories'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:200',
            'category_id'      => 'nullable|exists:medicine_categories,id',
            'generic_name'     => 'nullable|string|max:200',
            'brand'            => 'nullable|string|max:200',
            'type'             => 'nullable|string|max:50',
            'unit'             => 'required|string|max:20',
            'purchase_price'   => 'required|numeric|min:0',
            'selling_price'    => 'required|numeric|min:0',
            'stock_quantity'   => 'required|integer|min:0',
            'low_stock_alert'  => 'required|integer|min:0',
            'expiry_date'      => 'nullable|date',
            'manufacturer'     => 'nullable|string|max:200',
            'description'      => 'nullable|string',
            'status'           => 'required|in:active,inactive',
        ]);

        $medicine->update($validated);
        ActivityLog::log('update', 'Pharmacy', "Updated medicine: {$medicine->name}");

        return redirect()->route('pharmacy.index')->with('success', 'Medicine updated successfully.');
    }

    public function destroy(Medicine $medicine)
    {
        ActivityLog::log('delete', 'Pharmacy', "Deleted medicine: {$medicine->name}");
        $medicine->delete();
        return redirect()->route('pharmacy.index')->with('success', 'Medicine deleted.');
    }

    public function prescriptions(Request $request)
    {
        $prescriptions = Prescription::with(['patient', 'doctor', 'items.medicine'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        return view('pharmacy.prescriptions', compact('prescriptions'));
    }

    public function dispensePrescription(Request $request, Prescription $prescription)
    {
        foreach ($prescription->items as $item) {
            $medicine = $item->medicine;
            if ($medicine && $medicine->stock_quantity >= $item->quantity) {
                $medicine->decrement('stock_quantity', $item->quantity);
            }
        }

        $prescription->update(['status' => 'dispensed']);
        ActivityLog::log('dispense', 'Pharmacy', "Dispensed prescription: {$prescription->prescription_id}");

        return back()->with('success', 'Prescription dispensed successfully.');
    }

    public function sales(Request $request)
    {
        $sales = MedicineSale::with(['patient', 'items.medicine'])
            ->latest()
            ->paginate(15);
        $medicines = Medicine::where('status', 'active')->where('stock_quantity', '>', 0)->get();
        $patients  = Patient::where('status', 'active')->get();

        return view('pharmacy.sales', compact('sales', 'medicines', 'patients'));
    }

    public function storeSale(Request $request)
    {
        $request->validate([
            'patient_id'     => 'nullable|exists:patients,id',
            'items'          => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.quantity'    => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,card,mobile_money',
        ]);

        $totalAmount = 0;
        $saleItems   = [];

        foreach ($request->items as $item) {
            $medicine = Medicine::findOrFail($item['medicine_id']);
            $total    = $item['quantity'] * $medicine->selling_price;
            $totalAmount += $total;
            $saleItems[] = [
                'medicine_id' => $medicine->id,
                'quantity'    => $item['quantity'],
                'unit_price'  => $medicine->selling_price,
                'total'       => $total,
            ];
            $medicine->decrement('stock_quantity', $item['quantity']);
        }

        $discount = $request->discount ?? 0;
        $sale = MedicineSale::create([
            'patient_id'     => $request->patient_id,
            'sale_date'      => now()->toDateString(),
            'total_amount'   => $totalAmount,
            'discount'       => $discount,
            'paid_amount'    => $totalAmount - $discount,
            'payment_method' => $request->payment_method,
            'sold_by'        => auth()->id(),
        ]);

        foreach ($saleItems as $sItem) {
            $sItem['sale_id'] = $sale->id;
            MedicineSaleItem::create($sItem);
        }

        ActivityLog::log('create', 'Pharmacy', "Medicine sale: {$sale->sale_id}");

        return redirect()->route('pharmacy.sales')->with('success', 'Sale recorded successfully.');
    }
}
