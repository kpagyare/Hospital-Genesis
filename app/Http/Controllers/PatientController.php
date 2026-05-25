<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                  ->orWhere('last_name', 'like', "%{$request->search}%")
                  ->orWhere('patient_id', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->gender) {
            $query->where('gender', $request->gender);
        }

        $patients = $query->latest()->paginate(15);

        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'                => 'required|string|max:100',
            'last_name'                 => 'required|string|max:100',
            'email'                     => 'nullable|email|unique:patients,email',
            'phone'                     => 'required|string|max:20',
            'date_of_birth'             => 'nullable|date',
            'gender'                    => 'required|in:male,female,other',
            'blood_group'               => 'nullable|string|max:5',
            'address'                   => 'nullable|string',
            'city'                      => 'nullable|string|max:100',
            'state'                     => 'nullable|string|max:100',
            'country'                   => 'nullable|string|max:100',
            'emergency_contact_name'    => 'nullable|string|max:100',
            'emergency_contact_phone'   => 'nullable|string|max:20',
            'emergency_contact_relation'=> 'nullable|string|max:50',
            'medical_history'           => 'nullable|string',
            'allergies'                 => 'nullable|string',
            'photo'                     => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('patients', 'public');
        }

        // Create user account for patient
        $user = User::create([
            'name'     => $validated['first_name'] . ' ' . $validated['last_name'],
            'email'    => $validated['email'] ?? strtolower(str_replace(' ', '', $validated['first_name'])) . rand(100, 999) . '@hms.local',
            'password' => Hash::make('patient123'),
            'role'     => 'patient',
            'phone'    => $validated['phone'],
        ]);

        $validated['user_id'] = $user->id;
        $patient = Patient::create($validated);

        ActivityLog::log('create', 'Patient', "Created patient: {$patient->full_name}");

        return redirect()->route('patients.show', $patient)
            ->with('success', "Patient {$patient->full_name} created successfully. Default password: patient123");
    }

    public function show(Patient $patient)
    {
        $patient->load([
            'appointments.doctor',
            'invoices',
            'prescriptions.doctor',
            'labResults.labTest',
            'admissions.bed.ward',
        ]);
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'first_name'                => 'required|string|max:100',
            'last_name'                 => 'required|string|max:100',
            'email'                     => 'nullable|email|unique:patients,email,' . $patient->id,
            'phone'                     => 'required|string|max:20',
            'date_of_birth'             => 'nullable|date',
            'gender'                    => 'required|in:male,female,other',
            'blood_group'               => 'nullable|string|max:5',
            'address'                   => 'nullable|string',
            'city'                      => 'nullable|string|max:100',
            'state'                     => 'nullable|string|max:100',
            'country'                   => 'nullable|string|max:100',
            'emergency_contact_name'    => 'nullable|string|max:100',
            'emergency_contact_phone'   => 'nullable|string|max:20',
            'emergency_contact_relation'=> 'nullable|string|max:50',
            'medical_history'           => 'nullable|string',
            'allergies'                 => 'nullable|string',
            'status'                    => 'required|in:active,inactive,deceased',
            'photo'                     => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($patient->photo) Storage::disk('public')->delete($patient->photo);
            $validated['photo'] = $request->file('photo')->store('patients', 'public');
        }

        $patient->update($validated);
        ActivityLog::log('update', 'Patient', "Updated patient: {$patient->full_name}");

        return redirect()->route('patients.show', $patient)->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        if ($patient->photo) Storage::disk('public')->delete($patient->photo);
        ActivityLog::log('delete', 'Patient', "Deleted patient: {$patient->full_name}");
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Patient deleted successfully.');
    }
}
