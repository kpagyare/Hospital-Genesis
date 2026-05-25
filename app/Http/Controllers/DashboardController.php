<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_patients'     => Patient::count(),
            'total_doctors'      => Doctor::count(),
            'total_appointments' => Appointment::count(),
            'total_staff'        => Staff::count(),
            'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
            'total_revenue'      => Payment::sum('amount'),
            'monthly_revenue'    => Payment::whereMonth('payment_date', now()->month)
                                          ->whereYear('payment_date', now()->year)
                                          ->sum('amount'),
            'total_expenses'     => Expense::whereMonth('expense_date', now()->month)
                                          ->whereYear('expense_date', now()->year)
                                          ->sum('amount'),
        ];

        $recentAppointments = Appointment::with(['patient', 'doctor'])
            ->latest()
            ->take(6)
            ->get();

        $recentPatients = Patient::latest()->take(5)->get();

        // Monthly revenue for chart (last 6 months)
        $monthlyRevenue = [];
        $monthlyLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyLabels[] = $date->format('M Y');
            $monthlyRevenue[] = Payment::whereMonth('payment_date', $date->month)
                ->whereYear('payment_date', $date->year)
                ->sum('amount');
        }

        // Appointment status breakdown for pie chart
        $appointmentStats = Appointment::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        return view('dashboard', compact(
            'stats', 'recentAppointments', 'recentPatients',
            'monthlyRevenue', 'monthlyLabels', 'appointmentStats'
        ));
    }
}
