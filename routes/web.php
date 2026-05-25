<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\LaboratoryController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\WardController;
use Illuminate\Support\Facades\Route;

// ── Auth ────────────────────────────────────────────────────────────────────
Route::get('/',      [LoginController::class, 'showLoginForm'])->name('home');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── Protected Routes ─────────────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile & Password
    Route::get('/profile',          [SettingController::class, 'profile'])->name('settings.profile');
    Route::put('/profile',          [SettingController::class, 'updateProfile'])->name('settings.profile.update');
    Route::put('/profile/password', [SettingController::class, 'updatePassword'])->name('settings.password.update');

    // ── Patients ────────────────────────────────────────────────────────────
    Route::middleware(['role:super_admin,doctor,nurse,receptionist'])->group(function () {
        Route::resource('patients', PatientController::class);
    });

    // ── Doctors ─────────────────────────────────────────────────────────────
    Route::middleware(['role:super_admin,receptionist'])->group(function () {
        Route::resource('doctors', DoctorController::class);
    });

    // ── Appointments ─────────────────────────────────────────────────────────
    Route::middleware(['role:super_admin,doctor,nurse,receptionist'])->group(function () {
        Route::get('/appointments/calendar', [AppointmentController::class, 'calendar'])->name('appointments.calendar');
        Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');
        Route::resource('appointments', AppointmentController::class);
    });

    // ── Billing & Accounts ───────────────────────────────────────────────────
    Route::middleware(['role:super_admin,accountant,receptionist'])->group(function () {
        Route::get('/billing',            [BillingController::class, 'index'])->name('billing.index');
        Route::get('/billing/create',     [BillingController::class, 'create'])->name('billing.create');
        Route::post('/billing',           [BillingController::class, 'store'])->name('billing.store');
        Route::get('/billing/{invoice}',  [BillingController::class, 'show'])->name('billing.show');
        Route::get('/billing/{invoice}/edit', [BillingController::class, 'edit'])->name('billing.edit');
        Route::post('/billing/{invoice}/payment', [BillingController::class, 'addPayment'])->name('billing.payment');
        Route::get('/billing/{invoice}/print',    [BillingController::class, 'printInvoice'])->name('billing.print');
        Route::get('/expenses',      [BillingController::class, 'expenses'])->name('billing.expenses');
        Route::post('/expenses',     [BillingController::class, 'storeExpense'])->name('billing.expenses.store');
    });

    // ── Pharmacy ─────────────────────────────────────────────────────────────
    Route::middleware(['role:super_admin,pharmacist'])->group(function () {
        Route::get('/pharmacy',                    [PharmacyController::class, 'index'])->name('pharmacy.index');
        Route::get('/pharmacy/create',             [PharmacyController::class, 'create'])->name('pharmacy.create');
        Route::post('/pharmacy',                   [PharmacyController::class, 'store'])->name('pharmacy.store');
        Route::get('/pharmacy/{medicine}/edit',    [PharmacyController::class, 'edit'])->name('pharmacy.edit');
        Route::put('/pharmacy/{medicine}',         [PharmacyController::class, 'update'])->name('pharmacy.update');
        Route::delete('/pharmacy/{medicine}',      [PharmacyController::class, 'destroy'])->name('pharmacy.destroy');
        Route::get('/pharmacy/prescriptions',      [PharmacyController::class, 'prescriptions'])->name('pharmacy.prescriptions');
        Route::post('/pharmacy/prescriptions/{prescription}/dispense', [PharmacyController::class, 'dispensePrescription'])->name('pharmacy.dispense');
        Route::get('/pharmacy/sales',              [PharmacyController::class, 'sales'])->name('pharmacy.sales');
        Route::post('/pharmacy/sales',             [PharmacyController::class, 'storeSale'])->name('pharmacy.sales.store');
    });

    // ── Laboratory ───────────────────────────────────────────────────────────
    Route::middleware(['role:super_admin,lab_staff,doctor'])->group(function () {
        Route::get('/laboratory',                  [LaboratoryController::class, 'index'])->name('laboratory.index');
        Route::get('/laboratory/tests',            [LaboratoryController::class, 'tests'])->name('laboratory.tests');
        Route::post('/laboratory/tests',           [LaboratoryController::class, 'storeTest'])->name('laboratory.tests.store');
        Route::get('/laboratory/categories',       [LaboratoryController::class, 'categories'])->name('laboratory.categories');
        Route::post('/laboratory/categories',      [LaboratoryController::class, 'storeCategory'])->name('laboratory.categories.store');
        Route::get('/laboratory/create',           [LaboratoryController::class, 'create'])->name('laboratory.create');
        Route::post('/laboratory',                 [LaboratoryController::class, 'store'])->name('laboratory.store');
        Route::get('/laboratory/{laboratory}',     [LaboratoryController::class, 'show'])->name('laboratory.show');
        Route::put('/laboratory/{laboratory}',     [LaboratoryController::class, 'updateResult'])->name('laboratory.update');
    });

    // ── Wards & Beds ─────────────────────────────────────────────────────────
    Route::middleware(['role:super_admin,nurse,receptionist'])->group(function () {
        Route::resource('wards', WardController::class);
        Route::get('/admissions',                     [WardController::class, 'admissions'])->name('wards.admissions');
        Route::get('/admissions/admit',               [WardController::class, 'admit'])->name('wards.admit');
        Route::post('/admissions',                    [WardController::class, 'storeAdmission'])->name('wards.admissions.store');
        Route::post('/admissions/{admission}/discharge', [WardController::class, 'discharge'])->name('wards.discharge');
    });

    // ── Staff ────────────────────────────────────────────────────────────────
    Route::middleware(['role:super_admin'])->group(function () {
        Route::resource('staff', StaffController::class);
    });

    // ── Reports ──────────────────────────────────────────────────────────────
    Route::middleware(['role:super_admin,accountant'])->group(function () {
        Route::get('/reports',                  [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/patients',         [ReportController::class, 'patients'])->name('reports.patients');
        Route::get('/reports/revenue',          [ReportController::class, 'revenue'])->name('reports.revenue');
        Route::get('/reports/appointments',     [ReportController::class, 'appointments'])->name('reports.appointments');
        Route::get('/reports/export/{type}',    [ReportController::class, 'exportPdf'])->name('reports.export');
    });

    // ── Settings ─────────────────────────────────────────────────────────────
    Route::middleware(['role:super_admin'])->group(function () {
        Route::get('/settings',                        [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings',                        [SettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/logo',                  [SettingController::class, 'uploadLogo'])->name('settings.logo');
        Route::get('/settings/activity-logs',          [SettingController::class, 'activityLogs'])->name('settings.activity_logs');
        Route::get('/settings/users',                  [SettingController::class, 'manageUsers'])->name('settings.users');
        Route::patch('/settings/users/{user}/toggle',  [SettingController::class, 'toggleUserStatus'])->name('settings.users.toggle');
    });
});
