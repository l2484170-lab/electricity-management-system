<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Customer;
use App\Services\PaymentService;
use App\Services\SmsService;
use App\Models\SmsTemplate;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['customer', 'invoice']);
        if ($request->filled('customer_id')) { $query->where('customer_id', $request->customer_id); }
        if ($request->filled('invoice_id')) { $query->where('invoice_id', $request->invoice_id); }

        $payments = $query->orderByDesc('created_at')
                          ->skip($request->input('skip', 0))
                          ->take($request->input('limit', 100))
                          ->get();

        $result = $payments->map(function ($p) {
            $data = $p->toArray();
            $data['customer_name'] = $p->customer?->name;
            $data['invoice_number'] = $p->invoice?->invoice_number;
            return $data;
        });

        return response()->json($result);
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $invoice = Invoice::findOrFail($request->invoice_id);
        $customer = Customer::findOrFail($request->customer_id);

        if ($request->amount > $invoice->balance) {
            return response()->json(['message' => 'Payment amount exceeds balance'], 400);
        }

        $payment = Payment::create([
            'receipt_number' => PaymentService::generateReceiptNumber(),
            'invoice_id' => $request->invoice_id,
            'customer_id' => $request->customer_id,
            'amount' => $request->amount,
            'payment_method' => $request->input('payment_method', 'cash'),
            'notes' => $request->notes,
            'received_by' => $request->user()->id,
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

        $data = $payment->toArray();
        $data['customer_name'] = $customer->name;
        $data['invoice_number'] = $invoice->invoice_number;
        return response()->json($data, 201);
    }

    public function show(int $id)
    {
        $payment = Payment::with(['customer', 'invoice'])->findOrFail($id);
        $data = $payment->toArray();
        $data['customer_name'] = $payment->customer?->name;
        $data['invoice_number'] = $payment->invoice?->invoice_number;
        return response()->json($data);
    }
}
