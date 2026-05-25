<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                  ->orWhere('last_name', 'like', "%{$request->search}%")
                  ->orWhere('doctor_id', 'like', "%{$request->search}%")
                  ->orWhere('specialization', 'like', "%{$request->search}%");
            });
        }

        if ($request->specialization) {
            $query->where('specialization', $request->specialization);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $doctors = $query->latest()->paginate(12);
        $specializations = Doctor::distinct()->pluck('specialization');

        return view('doctors.index', compact('doctors', 'specializations'));
    }

    public function create()
    {
        return view('doctors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'email'            => 'required|email|unique:users,email',
            'phone'            => 'nullable|string|max:20',
            'specialization'   => 'required|string|max:100',
            'qualification'    => 'nullable|string|max:200',
            'experience_years' => 'nullable|integer|min:0',
            'consultation_fee' => 'nullable|numeric|min:0',
            'bio'              => 'nullable|string',
            'gender'           => 'nullable|in:male,female,other',
            'available_days'   => 'nullable|array',
            'available_from'   => 'nullable',
            'available_to'     => 'nullable',
            'photo'            => 'nullable|image|max:2048',
            'password'         => 'required|min:6',
        ]);

        $user = User::create([
            'name'     => $validated['first_name'] . ' ' . $validated['last_name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'doctor',
            'phone'    => $validated['phone'] ?? null,
        ]);

        $doctorData = $validated;
        $doctorData['user_id'] = $user->id;
        unset($doctorData['password']);

        if ($request->hasFile('photo')) {
            $doctorData['photo'] = $request->file('photo')->store('doctors', 'public');
        }

        $doctor = Doctor::create($doctorData);
        ActivityLog::log('create', 'Doctor', "Created doctor: {$doctor->full_name}");

        return redirect()->route('doctors.show', $doctor)
            ->with('success', "Dr. {$doctor->full_name} created successfully.");
    }

    public function show(Doctor $doctor)
    {
        $doctor->load(['appointments.patient', 'prescriptions.patient']);
        $todayAppointments = $doctor->appointments()
            ->whereDate('appointment_date', today())
            ->with('patient')
            ->get();
        return view('doctors.show', compact('doctor', 'todayAppointments'));
    }

    public function edit(Doctor $doctor)
    {
        return view('doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'phone'            => 'nullable|string|max:20',
            'specialization'   => 'required|string|max:100',
            'qualification'    => 'nullable|string|max:200',
            'experience_years' => 'nullable|integer|min:0',
            'consultation_fee' => 'nullable|numeric|min:0',
            'bio'              => 'nullable|string',
            'gender'           => 'nullable|in:male,female,other',
            'available_days'   => 'nullable|array',
            'available_from'   => 'nullable',
            'available_to'     => 'nullable',
            'status'           => 'required|in:active,on_leave,inactive',
            'photo'            => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($doctor->photo) Storage::disk('public')->delete($doctor->photo);
            $validated['photo'] = $request->file('photo')->store('doctors', 'public');
        }

        $doctor->update($validated);

        // Update user name
        if ($doctor->user) {
            $doctor->user->update(['name' => $validated['first_name'] . ' ' . $validated['last_name']]);
        }

        ActivityLog::log('update', 'Doctor', "Updated doctor: {$doctor->full_name}");

        return redirect()->route('doctors.show', $doctor)->with('success', 'Doctor updated successfully.');
    }

    public function destroy(Doctor $doctor)
    {
        if ($doctor->photo) Storage::disk('public')->delete($doctor->photo);
        ActivityLog::log('delete', 'Doctor', "Deleted doctor: {$doctor->full_name}");
        $doctor->delete();
        return redirect()->route('doctors.index')->with('success', 'Doctor deleted successfully.');
    }
}
