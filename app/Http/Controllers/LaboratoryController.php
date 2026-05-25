<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\LabResult;
use App\Models\LabTest;
use App\Models\LabTestCategory;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaboratoryController extends Controller
{
    public function index(Request $request)
    {
        $query = LabResult::with(['patient', 'doctor', 'labTest']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('result_id', 'like', "%{$request->search}%")
                  ->orWhereHas('patient', fn($p) => $p->where('first_name', 'like', "%{$request->search}%")
                      ->orWhere('last_name', 'like', "%{$request->search}%"))
                  ->orWhereHas('labTest', fn($t) => $t->where('name', 'like', "%{$request->search}%"));
            });
        }

        if ($request->status) $query->where('status', $request->status);
        if ($request->date)   $query->whereDate('test_date', $request->date);

        $results    = $query->latest()->paginate(15);
        $pendingCount = LabResult::where('status', 'pending')->count();
        $todayCount   = LabResult::whereDate('test_date', today())->count();

        return view('laboratory.index', compact('results', 'pendingCount', 'todayCount'));
    }

    public function tests(Request $request)
    {
        $tests      = LabTest::with('category')->latest()->paginate(15);
        $categories = LabTestCategory::all();
        return view('laboratory.tests', compact('tests', 'categories'));
    }

    public function storeTest(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:200',
            'category_id'  => 'nullable|exists:lab_test_categories,id',
            'price'        => 'required|numeric|min:0',
            'normal_range' => 'nullable|string|max:100',
            'unit'         => 'nullable|string|max:50',
            'description'  => 'nullable|string',
        ]);

        // Generate test_code
        $last = LabTest::latest()->first();
        $number = $last ? ((int) substr($last->test_code, 4)) + 1 : 1;
        $validated['test_code'] = 'LAB-' . str_pad($number, 4, '0', STR_PAD_LEFT);

        LabTest::create($validated);
        return back()->with('success', 'Lab test created successfully.');
    }

    public function create()
    {
        $patients = Patient::where('status', 'active')->get();
        $doctors  = Doctor::where('status', 'active')->get();
        $labTests = LabTest::where('status', 'active')->get();
        return view('laboratory.create', compact('patients', 'doctors', 'labTests'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'     => 'required|exists:patients,id',
            'doctor_id'      => 'required|exists:doctors,id',
            'lab_test_id'    => 'required|exists:lab_tests,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'test_date'      => 'required|date',
        ]);

        $validated['performed_by'] = auth()->id();
        $validated['status']       = 'pending';

        $result = LabResult::create($validated);
        ActivityLog::log('create', 'Laboratory', "Lab test requested: {$result->result_id}");

        return redirect()->route('laboratory.show', $result)
            ->with('success', 'Lab test request created successfully.');
    }

    public function show(LabResult $laboratory)
    {
        $laboratory->load(['patient', 'doctor', 'labTest', 'performedBy']);
        return view('laboratory.show', compact('laboratory'));
    }

    public function updateResult(Request $request, LabResult $laboratory)
    {
        $request->validate([
            'result_value' => 'nullable|string|max:500',
            'remarks'      => 'nullable|string',
            'status'       => 'required|in:pending,in_progress,completed,cancelled',
            'report_file'  => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);

        $data = $request->only('result_value', 'remarks', 'status');

        if ($request->hasFile('report_file')) {
            if ($laboratory->report_file) Storage::disk('public')->delete($laboratory->report_file);
            $data['report_file'] = $request->file('report_file')->store('lab_reports', 'public');
        }

        $laboratory->update($data);
        ActivityLog::log('update', 'Laboratory', "Lab result updated: {$laboratory->result_id}");

        return back()->with('success', 'Lab result updated successfully.');
    }

    public function categories(Request $request)
    {
        $categories = LabTestCategory::withCount('tests')->paginate(15);
        return view('laboratory.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100|unique:lab_test_categories']);
        LabTestCategory::create(['name' => $request->name]);
        return back()->with('success', 'Category added successfully.');
    }
}
