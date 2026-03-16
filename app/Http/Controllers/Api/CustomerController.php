<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::where('is_archived', $request->boolean('is_archived', false));

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%");
            });
        }

        return response()->json(
            $query->skip($request->input('skip', 0))
                  ->take($request->input('limit', 100))
                  ->get()
        );
    }

    public function count(Request $request)
    {
        $total = Customer::where('is_archived', false)->count();
        $active = Customer::where('is_active', true)->where('is_archived', false)->count();
        return response()->json(['total' => $total, 'active' => $active]);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $customer = Customer::create($request->only([
            'name', 'phone', 'email', 'address', 'national_id', 'opening_reading', 'notes',
        ]));

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'created',
            'entity_type' => 'customer',
            'entity_id' => $customer->id,
            'details' => "Created customer: {$customer->name}",
        ]);

        return response()->json($customer, 201);
    }

    public function show(int $id)
    {
        return response()->json(Customer::findOrFail($id));
    }

    public function update(Request $request, int $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->update($request->only([
            'name', 'phone', 'email', 'address', 'national_id', 'opening_reading', 'is_active', 'is_archived', 'notes',
        ]));

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'updated',
            'entity_type' => 'customer',
            'entity_id' => $customer->id,
            'details' => "Updated customer: {$customer->name}",
        ]);

        return response()->json($customer);
    }

    public function archive(Request $request, int $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->update(['is_archived' => true]);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'archived',
            'entity_type' => 'customer',
            'entity_id' => $customer->id,
            'details' => "Archived customer: {$customer->name}",
        ]);

        return response()->json(['message' => 'Customer archived successfully']);
    }

    public function activity(int $id)
    {
        $logs = ActivityLog::where('entity_type', 'customer')
            ->where('entity_id', $id)
            ->orderByDesc('created_at')
            ->get(['id', 'action', 'details', 'created_at']);

        return response()->json($logs);
    }
}
