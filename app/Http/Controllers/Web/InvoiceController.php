<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Customer;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('customer');
        if ($request->filled('status')) { $query->where('status', $request->status); }
        if ($request->filled('month')) { $query->where('month', $request->month); }
        $invoices = $query->orderByDesc('created_at')->paginate(20);
        return view('invoices.index', compact('invoices'));
    }

    public function generate(Request $request)
    {
        $request->validate(['month' => 'required|string|size:7']);
        $result = InvoiceService::generateFromReadings($request->month, auth()->id());
        return redirect()->route('invoices.index')
            ->with('success', "تم إنشاء {$result['generated']} فاتورة، تم تخطي {$result['skipped']}");
    }

    public function manualCreate()
    {
        $customers = Customer::where('is_archived', false)->get();
        return view('invoices.manual_create', compact('customers'));
    }

    public function manualStore(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'month' => 'required|string|size:7',
            'total_amount' => 'required|numeric|min:0',
        ]);

        Invoice::create([
            'invoice_number' => InvoiceService::generateInvoiceNumber(),
            'customer_id' => $request->customer_id,
            'month' => $request->month,
            'consumption' => 0, 'unit_price' => 0, 'consumption_amount' => 0,
            'fixed_fee' => $request->input('fixed_fee', 0),
            'total_amount' => $request->total_amount,
            'balance' => $request->total_amount,
            'status' => 'unpaid',
            'due_date' => $request->due_date ?? now()->addDays(30),
            'notes' => $request->notes,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('invoices.index')->with('success', 'تم إنشاء الفاتورة');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'payments']);
        return view('invoices.show', compact('invoice'));
    }

    public function checkOverdue()
    {
        $count = InvoiceService::checkOverdue();
        return redirect()->route('invoices.index')->with('success', "تم تحديد {$count} فاتورة كمتأخرة");
    }
}
