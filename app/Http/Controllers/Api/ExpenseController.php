<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function listCategories()
    {
        return response()->json(ExpenseCategory::all());
    }

    public function createCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:expense_categories,name']);
        $cat = ExpenseCategory::create($request->only(['name', 'description']));
        return response()->json($cat, 201);
    }

    public function index(Request $request)
    {
        $query = Expense::with('category');
        if ($request->filled('category_id')) { $query->where('category_id', $request->category_id); }

        $expenses = $query->orderByDesc('expense_date')
                          ->skip($request->input('skip', 0))
                          ->take($request->input('limit', 100))
                          ->get();

        $result = $expenses->map(function ($exp) {
            $data = $exp->toArray();
            $data['category_name'] = $exp->category?->name;
            return $data;
        });

        return response()->json($result);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'expense_date' => 'required|date',
        ]);

        $expense = Expense::create(array_merge(
            $request->only(['category_id', 'amount', 'description', 'receipt_image', 'expense_date']),
            ['recorded_by' => $request->user()->id]
        ));

        $data = $expense->toArray();
        $data['category_name'] = $expense->category?->name;
        return response()->json($data, 201);
    }

    public function show(int $id)
    {
        $expense = Expense::with('category')->findOrFail($id);
        $data = $expense->toArray();
        $data['category_name'] = $expense->category?->name;
        return response()->json($data);
    }
}
