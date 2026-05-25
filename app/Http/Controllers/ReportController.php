<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\LabResult;
use App\Models\Patient;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function patients(Request $request)
    {
        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo   = $request->date_to   ?? now()->format('Y-m-d');

        $patients = Patient::whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->when($request->gender, fn($q) => $q->where('gender', $request->gender))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->paginate(20);

        $total  = $patients->total();
        $male   = Patient::whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])->where('gender', 'male')->count();
        $female = Patient::whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])->where('gender', 'female')->count();

        return view('reports.patients', compact('patients', 'dateFrom', 'dateTo', 'total', 'male', 'female'));
    }

    public function revenue(Request $request)
    {
        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo   = $request->date_to   ?? now()->format('Y-m-d');

        $payments = Payment::with(['patient', 'invoice'])
            ->whereBetween('payment_date', [$dateFrom, $dateTo])
            ->paginate(20);

        $totalRevenue  = Payment::whereBetween('payment_date', [$dateFrom, $dateTo])->sum('amount');
        $totalExpenses = Expense::whereBetween('expense_date', [$dateFrom, $dateTo])->sum('amount');
        $netProfit     = $totalRevenue - $totalExpenses;

        $byMethod = Payment::whereBetween('payment_date', [$dateFrom, $dateTo])
            ->select('payment_method', DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->get();

        return view('reports.revenue', compact(
            'payments', 'dateFrom', 'dateTo', 'totalRevenue', 'totalExpenses', 'netProfit', 'byMethod'
        ));
    }

    public function appointments(Request $request)
    {
        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo   = $request->date_to   ?? now()->format('Y-m-d');

        $appointments = Appointment::with(['patient', 'doctor'])
            ->whereBetween('appointment_date', [$dateFrom, $dateTo])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->paginate(20);

        $statusCounts = Appointment::whereBetween('appointment_date', [$dateFrom, $dateTo])
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('reports.appointments', compact('appointments', 'dateFrom', 'dateTo', 'statusCounts'));
    }

    public function exportPdf(Request $request, string $type)
    {
        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo   = $request->date_to   ?? now()->format('Y-m-d');

        $data = match($type) {
            'revenue' => [
                'payments'      => Payment::with('patient')->whereBetween('payment_date', [$dateFrom, $dateTo])->get(),
                'totalRevenue'  => Payment::whereBetween('payment_date', [$dateFrom, $dateTo])->sum('amount'),
                'totalExpenses' => Expense::whereBetween('expense_date', [$dateFrom, $dateTo])->sum('amount'),
            ],
            'patients' => [
                'patients' => Patient::whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])->get(),
            ],
            default => [],
        };

        $settings = \App\Models\Setting::first();
        $pdf = Pdf::loadView("reports.pdf.{$type}", array_merge($data, compact('dateFrom', 'dateTo', 'settings')));
        return $pdf->stream("report-{$type}-{$dateFrom}-to-{$dateTo}.pdf");
    }
}
