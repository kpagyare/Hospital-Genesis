<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::first() ?? new Setting();
        $users    = User::latest()->paginate(15);
        return view('settings.index', compact('settings', 'users'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hospital_name' => 'required|string|max:200',
            'email'         => 'nullable|email',
            'phone'         => 'nullable|string|max:30',
            'address'       => 'nullable|string',
            'website'       => 'nullable|string|max:200',
            'currency'      => 'nullable|string|max:5',
            'timezone'      => 'nullable|string|max:50',
            'date_format'   => 'nullable|string|max:20',
            'per_page'      => 'nullable|integer|min:5|max:200',
            'footer_text'   => 'nullable|string',
        ]);

        $data = $request->only(['hospital_name','email','phone','address','website','currency','timezone','date_format','per_page','footer_text']);

        $settings = Setting::first() ?? new Setting();
        $settings->fill($data)->save();

        ActivityLog::log('update', 'Settings', 'Hospital settings updated');

        return back()->with('success', 'Settings updated successfully.');
    }

    public function uploadLogo(Request $request)
    {
        $request->validate(['logo' => 'required|image|max:3072']);

        $settings = Setting::first() ?? new Setting();

        if ($settings->logo) {
            Storage::disk('public')->delete($settings->logo);
        }

        $path = $request->file('logo')->store('logo', 'public');
        $settings->logo = $path;
        $settings->save();

        ActivityLog::log('update', 'Settings', 'Hospital logo updated');

        return back()->with('success', 'Logo updated successfully.');
    }

    public function activityLogs(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->when($request->module, fn($q) => $q->where('module', $request->module))
            ->when($request->date,   fn($q) => $q->whereDate('created_at', $request->date))
            ->latest()
            ->paginate(20);

        $modules = ActivityLog::distinct()->pluck('module');

        return view('settings.activity_logs', compact('logs', 'modules'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('settings.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo) Storage::disk('public')->delete($user->photo);
            $validated['photo'] = $request->file('photo')->store('avatars', 'public');
        }

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password changed successfully.');
    }

    public function manageUsers(Request $request)
    {
        $users = User::latest()->paginate(15);
        return view('settings.users', compact('users'));
    }

    public function toggleUserStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot deactivate your own account.']);
        }
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User account {$status} successfully.");
    }
}
