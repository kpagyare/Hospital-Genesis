<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $query = Staff::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                  ->orWhere('last_name', 'like', "%{$request->search}%")
                  ->orWhere('staff_id', 'like', "%{$request->search}%")
                  ->orWhere('department', 'like', "%{$request->search}%");
            });
        }

        if ($request->department) $query->where('department', $request->department);
        if ($request->status)     $query->where('status', $request->status);

        $staff       = $query->latest()->paginate(12);
        $departments = Staff::distinct()->pluck('department')->filter();
        $roles       = ['nurse', 'receptionist', 'pharmacist', 'lab_staff', 'accountant'];

        return view('staff.index', compact('staff', 'departments', 'roles'));
    }

    public function create()
    {
        $roles = ['nurse', 'receptionist', 'pharmacist', 'lab_staff', 'accountant'];
        return view('staff.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'        => 'required|string|max:100',
            'last_name'         => 'required|string|max:100',
            'email'             => 'required|email|unique:users,email',
            'phone'             => 'nullable|string|max:20',
            'department'        => 'nullable|string|max:100',
            'position'          => 'required|string|max:100',
            'role'              => 'required|in:nurse,receptionist,pharmacist,lab_staff,accountant',
            'join_date'         => 'nullable|date',
            'salary'            => 'nullable|numeric|min:0',
            'gender'            => 'nullable|in:male,female,other',
            'address'           => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:200',
            'password'          => 'required|min:6',
            'photo'             => 'nullable|image|max:2048',
        ]);

        $user = User::create([
            'name'     => $validated['first_name'] . ' ' . $validated['last_name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
            'phone'    => $validated['phone'] ?? null,
        ]);

        $staffData = $validated;
        $staffData['user_id'] = $user->id;
        unset($staffData['password'], $staffData['role']);

        if ($request->hasFile('photo')) {
            $staffData['photo'] = $request->file('photo')->store('staff', 'public');
        }

        $staff = Staff::create($staffData);
        ActivityLog::log('create', 'Staff', "Created staff: {$staff->full_name}");

        return redirect()->route('staff.show', $staff)->with('success', 'Staff member created successfully.');
    }

    public function show(Staff $staff)
    {
        $staff->load('user');
        return view('staff.show', compact('staff'));
    }

    public function edit(Staff $staff)
    {
        $roles = ['nurse', 'receptionist', 'pharmacist', 'lab_staff', 'accountant'];
        return view('staff.edit', compact('staff', 'roles'));
    }

    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'first_name'        => 'required|string|max:100',
            'last_name'         => 'required|string|max:100',
            'phone'             => 'nullable|string|max:20',
            'department'        => 'nullable|string|max:100',
            'position'          => 'required|string|max:100',
            'join_date'         => 'nullable|date',
            'salary'            => 'nullable|numeric|min:0',
            'gender'            => 'nullable|in:male,female,other',
            'address'           => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:200',
            'status'            => 'required|in:active,on_leave,terminated',
            'photo'             => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($staff->photo) Storage::disk('public')->delete($staff->photo);
            $validated['photo'] = $request->file('photo')->store('staff', 'public');
        }

        $staff->update($validated);
        if ($staff->user) {
            $staff->user->update(['name' => $validated['first_name'] . ' ' . $validated['last_name']]);
        }

        ActivityLog::log('update', 'Staff', "Updated staff: {$staff->full_name}");

        return redirect()->route('staff.show', $staff)->with('success', 'Staff updated successfully.');
    }

    public function destroy(Staff $staff)
    {
        ActivityLog::log('delete', 'Staff', "Deleted staff: {$staff->full_name}");
        $staff->delete();
        return redirect()->route('staff.index')->with('success', 'Staff member removed.');
    }
}
