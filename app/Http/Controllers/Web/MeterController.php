<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Meter;
use App\Models\Customer;
use Illuminate\Http\Request;

class MeterController extends Controller
{
    public function index(Request $request)
    {
        $query = Meter::with('customer');
        if ($request->filled('status')) { $query->where('status', $request->status); }
        $meters = $query->paginate(20);
        return view('meters.index', compact('meters'));
    }

    public function create()
    {
        $customers = Customer::where('is_archived', false)->get();
        return view('meters.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate(['meter_number' => 'required|string|unique:meters,meter_number']);
        Meter::create($request->only(['meter_number', 'customer_id', 'status', 'location', 'meter_type', 'installation_date']));
        return redirect()->route('meters.index')->with('success', 'تم إنشاء العداد بنجاح');
    }

    public function edit(Meter $meter)
    {
        $customers = Customer::where('is_archived', false)->get();
        return view('meters.edit', compact('meter', 'customers'));
    }

    public function update(Request $request, Meter $meter)
    {
        $meter->update($request->only(['meter_number', 'customer_id', 'status', 'location', 'meter_type', 'installation_date']));
        return redirect()->route('meters.index')->with('success', 'تم تحديث العداد بنجاح');
    }
}
