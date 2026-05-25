<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Appointment;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Patient;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['patient']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_number', 'like', "%{$request->search}%")
                  ->orWhereHas('patient', fn($p) => $p->where('first_name', 'like', "%{$request->search}%")
                      ->orWhere('last_name', 'like', "%{$request->search}%"));
            });
        }

        if ($request->status) $query->where('status', $request->status);

        $invoices = $query->latest()->paginate(15);
        $totalRevenue = Payment::sum('amount');
        $monthlyRevenue = Payment::whereMonth('payment_date', now()->month)->sum('amount');
        $pendingAmount = Invoice::whereIn('status', ['sent', 'partially_paid'])->sum('due_amount');

        return view('billing.index', compact('invoices', 'totalRevenue', 'monthlyRevenue', 'pendingAmount'));
    }

    public function create()
    {
        $patients     = Patient::where('status', 'active')->get();
        $appointments = Appointment::where('status', 'completed')->whereDoesntHave('invoice')->get();
        return view('billing.create', compact('patients', 'appointments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id'    => 'required|exists:patients,id',
            'invoice_date'  => 'required|date',
            'due_date'      => 'nullable|date|after_or_equal:invoice_date',
            'items'         => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity'    => 'required|integer|min:1',
            'items.*.unit_price'  => 'required|numeric|min:0',
            'discount'      => 'nullable|numeric|min:0',
            'tax'           => 'nullable|numeric|min:0',
            'notes'         => 'nullable|string',
        ]);

        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }

        $discount = $request->discount ?? 0;
        $tax      = $request->tax ?? 0;
        $total    = $subtotal - $discount + $tax;

        $invoice = Invoice::create([
            'patient_id'     => $request->patient_id,
            'appointment_id' => $request->appointment_id ?? null,
            'invoice_date'   => $request->invoice_date,
            'due_date'       => $request->due_date,
            'subtotal'       => $subtotal,
            'discount'       => $discount,
            'tax'            => $tax,
            'total_amount'   => $total,
            'paid_amount'    => 0,
            'due_amount'     => $total,
            'status'         => 'sent',
            'notes'          => $request->notes,
            'created_by'     => auth()->id(),
        ]);

        foreach ($request->items as $item) {
            InvoiceItem::create([
                'invoice_id'  => $invoice->id,
                'description' => $item['description'],
                'item_type'   => $item['item_type'] ?? 'other',
                'quantity'    => $item['quantity'],
                'unit_price'  => $item['unit_price'],
                'total'       => $item['quantity'] * $item['unit_price'],
            ]);
        }

        ActivityLog::log('create', 'Billing', "Created invoice: {$invoice->invoice_number}");

        return redirect()->route('billing.show', $invoice)
            ->with('success', "Invoice {$invoice->invoice_number} created successfully.");
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['patient', 'items', 'payments.receivedBy', 'appointment', 'admission']);
        return view('billing.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $patients = Patient::where('status', 'active')->get();
        return view('billing.edit', compact('invoice', 'patients'));
    }

    public function addPayment(Request $request, Invoice $invoice)
    {
        $request->validate([
            'amount'         => 'required|numeric|min:0.01|max:' . $invoice->due_amount,
            'payment_date'   => 'required|date',
            'payment_method' => 'required|in:cash,card,bank_transfer,insurance,mobile_money',
            'transaction_reference' => 'nullable|string',
            'notes'          => 'nullable|string',
        ]);

        $payment = Payment::create([
            'invoice_id'            => $invoice->id,
            'patient_id'            => $invoice->patient_id,
            'amount'                => $request->amount,
            'payment_date'          => $request->payment_date,
            'payment_method'        => $request->payment_method,
            'transaction_reference' => $request->transaction_reference,
            'notes'                 => $request->notes,
            'received_by'           => auth()->id(),
        ]);

        $newPaid = $invoice->paid_amount + $request->amount;
        $newDue  = $invoice->total_amount - $newPaid;
        $status  = $newDue <= 0 ? 'paid' : 'partially_paid';

        $invoice->update([
            'paid_amount' => $newPaid,
            'due_amount'  => max(0, $newDue),
            'status'      => $status,
        ]);

        ActivityLog::log('payment', 'Billing', "Payment recorded for invoice: {$invoice->invoice_number}");

        return redirect()->route('billing.show', $invoice)->with('success', 'Payment recorded successfully.');
    }

    public function printInvoice(Invoice $invoice)
    {
        $invoice->load(['patient', 'items', 'payments']);
        $settings = \App\Models\Setting::first();
        $pdf = Pdf::loadView('billing.print', compact('invoice', 'settings'));
        return $pdf->stream("invoice-{$invoice->invoice_number}.pdf");
    }

    // Expenses
    public function expenses(Request $request)
    {
        $query = Expense::with('createdBy');
        if ($request->search) {
            $query->where('title', 'like', "%{$request->search}%")
                  ->orWhere('category', 'like', "%{$request->search}%");
        }
        if ($request->month) {
            $query->whereMonth('expense_date', $request->month);
        }
        $expenses = $query->latest()->paginate(15);
        $totalExpenses = Expense::whereMonth('expense_date', now()->month)->sum('amount');
        return view('billing.expenses', compact('expenses', 'totalExpenses'));
    }

    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:200',
            'category'     => 'required|string|max:100',
            'amount'       => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'description'  => 'nullable|string',
        ]);
        $validated['created_by'] = auth()->id();
        $expense = Expense::create($validated);
        ActivityLog::log('create', 'Expense', "Created expense: {$expense->title}");
        return back()->with('success', 'Expense recorded successfully.');
    }
}
