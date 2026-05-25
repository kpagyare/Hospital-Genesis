<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('appointment_id', 'like', "%{$request->search}%")
                  ->orWhereHas('patient', fn($p) => $p->where('first_name', 'like', "%{$request->search}%")
                      ->orWhere('last_name', 'like', "%{$request->search}%"))
                  ->orWhereHas('doctor', fn($d) => $d->where('first_name', 'like', "%{$request->search}%")
                      ->orWhere('last_name', 'like', "%{$request->search}%"));
            });
        }

        if ($request->status) $query->where('status', $request->status);
        if ($request->doctor_id) $query->where('doctor_id', $request->doctor_id);
        if ($request->date) $query->whereDate('appointment_date', $request->date);

        $appointments = $query->latest()->paginate(15);
        $doctors = Doctor::where('status', 'active')->get();

        return view('appointments.index', compact('appointments', 'doctors'));
    }

    public function calendar()
    {
        $appointments = Appointment::with(['patient', 'doctor'])
            ->whereMonth('appointment_date', now()->month)
            ->get()
            ->map(fn($apt) => [
                'id'    => $apt->id,
                'title' => $apt->patient->full_name . ' - Dr. ' . $apt->doctor->last_name,
                'start' => $apt->appointment_date->format('Y-m-d') . 'T' . $apt->appointment_time,
                'color' => match($apt->status) {
                    'confirmed' => '#17a2b8',
                    'completed' => '#28a745',
                    'cancelled' => '#dc3545',
                    'no_show'   => '#6c757d',
                    default     => '#ffc107',
                },
                'url'   => route('appointments.show', $apt->id),
            ]);

        return view('appointments.calendar', ['events' => $appointments]);
    }

    public function create()
    {
        $patients = Patient::where('status', 'active')->get();
        $doctors  = Doctor::where('status', 'active')->get();
        return view('appointments.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'doctor_id'        => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'reason'           => 'nullable|string|max:500',
            'notes'            => 'nullable|string',
            'type'             => 'required|in:regular,emergency,follow_up',
            'fee'              => 'nullable|numeric|min:0',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status']     = 'pending';

        if (empty($validated['fee'])) {
            $doctor = Doctor::find($validated['doctor_id']);
            $validated['fee'] = $doctor->consultation_fee ?? 0;
        }

        $appointment = Appointment::create($validated);
        ActivityLog::log('create', 'Appointment', "Booked appointment: {$appointment->appointment_id}");

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment booked successfully.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor', 'prescription.items.medicine', 'labResults.labTest']);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $patients = Patient::where('status', 'active')->get();
        $doctors  = Doctor::where('status', 'active')->get();
        return view('appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'doctor_id'        => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'reason'           => 'nullable|string|max:500',
            'notes'            => 'nullable|string',
            'type'             => 'required|in:regular,emergency,follow_up',
            'status'           => 'required|in:pending,confirmed,completed,cancelled,no_show',
            'fee'              => 'nullable|numeric|min:0',
        ]);

        $appointment->update($validated);
        ActivityLog::log('update', 'Appointment', "Updated appointment: {$appointment->appointment_id}");

        return redirect()->route('appointments.show', $appointment)->with('success', 'Appointment updated successfully.');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,completed,cancelled,no_show']);
        $appointment->update(['status' => $request->status]);
        return back()->with('success', 'Appointment status updated.');
    }

    public function destroy(Appointment $appointment)
    {
        ActivityLog::log('delete', 'Appointment', "Deleted appointment: {$appointment->appointment_id}");
        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'Appointment deleted.');
    }
}
