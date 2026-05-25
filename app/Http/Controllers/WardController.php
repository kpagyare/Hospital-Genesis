<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Admission;
use App\Models\Bed;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Ward;
use Illuminate\Http\Request;

class WardController extends Controller
{
    public function index()
    {
        $wards = Ward::withCount(['beds', 'beds as available_beds_count' => fn($q) => $q->where('status', 'available')])
            ->paginate(12);
        $totalBeds     = Bed::count();
        $availableBeds = Bed::where('status', 'available')->count();
        $occupiedBeds  = Bed::where('status', 'occupied')->count();
        return view('wards.index', compact('wards', 'totalBeds', 'availableBeds', 'occupiedBeds'));
    }

    public function create()
    {
        return view('wards.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:100',
            'ward_type'          => 'nullable|string|max:50',
            'total_beds'         => 'required|integer|min:1',
            'bed_charge_per_day' => 'nullable|numeric|min:0',
            'description'        => 'nullable|string',
        ]);

        $ward = Ward::create($validated);

        // Auto-create beds
        for ($i = 1; $i <= $validated['total_beds']; $i++) {
            Bed::create([
                'bed_number'     => $ward->name . '-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'ward_id'        => $ward->id,
                'status'         => 'available',
                'charge_per_day' => $validated['bed_charge_per_day'] ?? 0,
            ]);
        }

        ActivityLog::log('create', 'Ward', "Created ward: {$ward->name}");

        return redirect()->route('wards.show', $ward)->with('success', "Ward created with {$validated['total_beds']} beds.");
    }

    public function show(Ward $ward)
    {
        $ward->load(['beds.currentAdmission.patient']);
        return view('wards.show', compact('ward'));
    }

    public function edit(Ward $ward)
    {
        return view('wards.edit', compact('ward'));
    }

    public function update(Request $request, Ward $ward)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:100',
            'ward_type'          => 'nullable|string|max:50',
            'bed_charge_per_day' => 'nullable|numeric|min:0',
            'description'        => 'nullable|string',
            'status'             => 'required|in:active,inactive',
        ]);
        $ward->update($validated);
        return redirect()->route('wards.show', $ward)->with('success', 'Ward updated successfully.');
    }

    // Admissions
    public function admissions(Request $request)
    {
        $query = Admission::with(['patient', 'doctor', 'bed.ward']);
        if ($request->status) $query->where('status', $request->status);
        $admissions = $query->latest()->paginate(15);
        return view('wards.admissions', compact('admissions'));
    }

    public function admit()
    {
        $patients      = Patient::where('status', 'active')->get();
        $doctors       = Doctor::where('status', 'active')->get();
        $availableBeds = Bed::where('status', 'available')->with('ward')->get();
        return view('wards.admit', compact('patients', 'doctors', 'availableBeds'));
    }

    public function storeAdmission(Request $request)
    {
        $validated = $request->validate([
            'patient_id'     => 'required|exists:patients,id',
            'doctor_id'      => 'required|exists:doctors,id',
            'bed_id'         => 'required|exists:beds,id',
            'admission_date' => 'required|date',
            'diagnosis'      => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        $admission = Admission::create($validated);

        // Mark bed as occupied
        Bed::find($validated['bed_id'])->update(['status' => 'occupied']);

        ActivityLog::log('create', 'Ward', "Patient admitted: {$admission->admission_id}");

        return redirect()->route('wards.admissions')->with('success', 'Patient admitted successfully.');
    }

    public function discharge(Request $request, Admission $admission)
    {
        $request->validate([
            'discharge_date'  => 'required|date|after_or_equal:' . $admission->admission_date->format('Y-m-d'),
            'discharge_notes' => 'nullable|string',
        ]);

        $days = $admission->admission_date->diffInDays($request->discharge_date) ?: 1;
        $totalCharges = $days * ($admission->bed->charge_per_day ?? 0);

        $admission->update([
            'discharge_date'  => $request->discharge_date,
            'discharge_notes' => $request->discharge_notes,
            'status'          => 'discharged',
            'total_charges'   => $totalCharges,
        ]);

        $admission->bed->update(['status' => 'available']);

        ActivityLog::log('discharge', 'Ward', "Patient discharged: {$admission->admission_id}");

        return redirect()->route('wards.admissions')->with('success', 'Patient discharged successfully.');
    }
}
