<?php

namespace App\Http\Controllers\Api;

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
        if ($request->filled('customer_id')) { $query->where('customer_id', $request->customer_id); }

        $invoices = $query->orderByDesc('created_at')
                          ->skip($request->input('skip', 0))
                          ->take($request->input('limit', 100))
                          ->get();

        $result = $invoices->map(function ($inv) {
            $data = $inv->toArray();
            $data['customer_name'] = $inv->customer?->name;
            return $data;
        });

        return response()->json($result);
    }

    public function generate(Request $request)
    {
        $request->validate(['month' => 'required|string|size:7']);
        $result = InvoiceService::generateFromReadings($request->month, $request->user()->id);
        return response()->json($result);
    }

    public function manualCreate(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'month' => 'required|string|size:7',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $customer = Customer::findOrFail($request->customer_id);

        $invoice = Invoice::create([
            'invoice_number' => InvoiceService::generateInvoiceNumber(),
            'customer_id' => $request->customer_id,
            'month' => $request->month,
            'consumption' => 0,
            'unit_price' => 0,
            'consumption_amount' => 0,
            'fixed_fee' => $request->input('fixed_fee', 0),
            'total_amount' => $request->total_amount,
            'balance' => $request->total_amount,
            'status' => 'unpaid',
            'due_date' => $request->due_date ?? now()->addDays(30),
            'notes' => $request->notes,
            'created_by' => $request->user()->id,
        ]);

        $data = $invoice->toArray();
        $data['customer_name'] = $customer->name;
        return response()->json($data, 201);
    }

    public function show(int $id)
    {
        $invoice = Invoice::with('customer')->findOrFail($id);
        $data = $invoice->toArray();
        $data['customer_name'] = $invoice->customer?->name;
        return response()->json($data);
    }

    public function update(Request $request, int $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update($request->only(['status', 'notes', 'due_date']));
        return response()->json($invoice);
    }

    public function checkOverdue()
    {
        $count = InvoiceService::checkOverdue();
        return response()->json(['overdue_count' => $count]);
    }
}
