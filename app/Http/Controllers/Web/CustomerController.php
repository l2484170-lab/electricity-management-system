<?php

namespace App\Http\Controllers\Web;

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

        $customers = $query->paginate(20);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $customer = Customer::create($request->only([
            'name', 'phone', 'email', 'address', 'national_id', 'opening_reading', 'notes',
        ]));

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'entity_type' => 'customer',
            'entity_id' => $customer->id,
            'details' => "تم إنشاء عميل: {$customer->name}",
        ]);

        return redirect()->route('customers.index')->with('success', 'تم إنشاء العميل بنجاح');
    }

    public function show(Customer $customer)
    {
        $customer->load(['meters', 'invoices', 'payments']);
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $customer->update($request->only([
            'name', 'phone', 'email', 'address', 'national_id', 'opening_reading', 'is_active', 'notes',
        ]));

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'entity_type' => 'customer',
            'entity_id' => $customer->id,
            'details' => "تم تحديث عميل: {$customer->name}",
        ]);

        return redirect()->route('customers.index')->with('success', 'تم تحديث العميل بنجاح');
    }

    public function archive(Customer $customer)
    {
        $customer->update(['is_archived' => true]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'archived',
            'entity_type' => 'customer',
            'entity_id' => $customer->id,
            'details' => "تم أرشفة عميل: {$customer->name}",
        ]);

        return redirect()->route('customers.index')->with('success', 'تم أرشفة العميل');
    }
}
