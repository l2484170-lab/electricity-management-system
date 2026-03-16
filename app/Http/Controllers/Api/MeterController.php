<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meter;
use App\Models\Customer;
use Illuminate\Http\Request;

class MeterController extends Controller
{
    public function index(Request $request)
    {
        $query = Meter::with('customer');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $meters = $query->skip($request->input('skip', 0))
                        ->take($request->input('limit', 100))
                        ->get();

        $result = $meters->map(function ($meter) {
            $data = $meter->toArray();
            $data['customer_name'] = $meter->customer?->name;
            return $data;
        });

        return response()->json($result);
    }

    public function store(Request $request)
    {
        $request->validate([
            'meter_number' => 'required|string|unique:meters,meter_number',
        ]);

        if ($request->filled('customer_id')) {
            Customer::findOrFail($request->customer_id);
        }

        $meter = Meter::create($request->only([
            'meter_number', 'customer_id', 'status', 'location', 'meter_type', 'installation_date',
        ]));

        return response()->json($meter, 201);
    }

    public function show(int $id)
    {
        $meter = Meter::with('customer')->findOrFail($id);
        $data = $meter->toArray();
        $data['customer_name'] = $meter->customer?->name;
        return response()->json($data);
    }

    public function update(Request $request, int $id)
    {
        $meter = Meter::findOrFail($id);
        $meter->update($request->only([
            'meter_number', 'customer_id', 'status', 'location', 'meter_type', 'installation_date',
        ]));
        return response()->json($meter);
    }
}
