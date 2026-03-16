<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Customer;
use App\Services\PaymentService;
use App\Services\SmsService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['customer', 'invoice']);
        if ($request->filled('customer_id')) { $query->where('customer_id', $request->customer_id); }
        $payments = $query->orderByDesc('created_at')->paginate(20);
        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $invoices = Invoice::whereIn('status', ['unpaid', 'partial', 'overdue'])->with('customer')->get();
        return view('payments.create', compact('invoices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $invoice = Invoice::findOrFail($request->invoice_id);
        $customer = Customer::findOrFail($invoice->customer_id);

        if ($request->amount > $invoice->balance) {
            return back()->withErrors(['amount' => 'المبلغ يتجاوز الرصيد المطلوب'])->withInput();
        }

        Payment::create([
            'receipt_number' => PaymentService::generateReceiptNumber(),
            'invoice_id' => $invoice->id,
            'customer_id' => $customer->id,
            'amount' => $request->amount,
            'payment_method' => $request->input('payment_method', 'cash'),
            'notes' => $request->notes,
            'received_by' => auth()->id(),
        ]);

        $invoice->paid_amount += $request->amount;
        $invoice->balance = $invoice->total_amount - $invoice->paid_amount;
        if ($invoice->balance <= 0) {
            $invoice->status = 'paid';
            $invoice->balance = 0;
        } elseif ($invoice->paid_amount > 0) {
            $invoice->status = 'partial';
        }
        $invoice->save();

        SmsService::sendNotification('payment_received', $customer, [
            'customer_name' => $customer->name,
            'total_amount' => number_format($request->amount, 2),
            'invoice_month' => $invoice->month,
        ]);

        return redirect()->route('payments.index')->with('success', 'تم تسجيل الدفعة');
    }
}
