<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with('category');
        if ($request->filled('category_id')) { $query->where('category_id', $request->category_id); }
        $expenses = $query->orderByDesc('expense_date')->paginate(20);
        $categories = ExpenseCategory::all();
        return view('expenses.index', compact('expenses', 'categories'));
    }

    public function create()
    {
        $categories = ExpenseCategory::all();
        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'expense_date' => 'required|date',
        ]);

        Expense::create(array_merge(
            $request->only(['category_id', 'amount', 'description', 'expense_date']),
            ['recorded_by' => auth()->id()]
        ));

        return redirect()->route('expenses.index')->with('success', 'تم تسجيل المصروف');
    }

    public function createCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:expense_categories,name']);
        ExpenseCategory::create($request->only(['name', 'description']));
        return back()->with('success', 'تم إنشاء الفئة');
    }
}
