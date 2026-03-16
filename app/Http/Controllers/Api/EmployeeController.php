<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Salary;
use App\Models\AdvanceDeduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::query();
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }
        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $emp = Employee::create($request->only([
            'name', 'phone', 'email', 'position', 'department',
            'base_salary', 'hire_date', 'national_id', 'address',
        ]));
        return response()->json($emp, 201);
    }

    public function show(int $id)
    {
        return response()->json(Employee::findOrFail($id));
    }

    public function update(Request $request, int $id)
    {
        $emp = Employee::findOrFail($id);
        $emp->update($request->only([
            'name', 'phone', 'email', 'position', 'department',
            'base_salary', 'is_active', 'national_id', 'address',
        ]));
        return response()->json($emp);
    }

    // Attendance
    public function recordAttendance(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
        ]);
        $att = Attendance::create($request->only([
            'employee_id', 'date', 'status', 'check_in', 'check_out', 'notes',
        ]));
        return response()->json($att, 201);
    }

    public function listAttendance(Request $request)
    {
        $query = Attendance::query();
        if ($request->filled('employee_id')) { $query->where('employee_id', $request->employee_id); }
        if ($request->filled('month')) {
            $query->whereRaw("strftime('%Y-%m', date) = ?", [$request->month]);
        }
        return response()->json($query->orderByDesc('date')->get());
    }

    // Salary
    public function generateSalary(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|string|size:7',
        ]);

        $emp = Employee::findOrFail($request->employee_id);

        $existing = Salary::where('employee_id', $request->employee_id)
            ->where('month', $request->month)->first();
        if ($existing) {
            return response()->json(['message' => 'Salary already generated for this month'], 400);
        }

        $advancesTotal = AdvanceDeduction::where('employee_id', $request->employee_id)
            ->where('month', $request->month)
            ->where('type', 'advance')
            ->sum('amount') ?? 0;

        $deductionsTotal = AdvanceDeduction::where('employee_id', $request->employee_id)
            ->where('month', $request->month)
            ->where('type', 'deduction')
            ->sum('amount') ?? 0;

        $bonuses = $request->input('bonuses', 0);
        $net = $emp->base_salary + $bonuses - (float) $deductionsTotal - (float) $advancesTotal;

        $salary = Salary::create([
            'employee_id' => $request->employee_id,
            'month' => $request->month,
            'base_salary' => $emp->base_salary,
            'deductions' => (float) $deductionsTotal,
            'advances' => (float) $advancesTotal,
            'bonuses' => $bonuses,
            'net_salary' => $net,
        ]);

        $data = $salary->toArray();
        $data['employee_name'] = $emp->name;
        return response()->json($data, 201);
    }

    public function listSalaries(Request $request)
    {
        $query = Salary::query();
        if ($request->filled('month')) { $query->where('month', $request->month); }
        if ($request->filled('employee_id')) { $query->where('employee_id', $request->employee_id); }

        $salaries = $query->get();
        $result = $salaries->map(function ($s) {
            $data = $s->toArray();
            $data['employee_name'] = $s->employee?->name;
            return $data;
        });
        return response()->json($result);
    }

    public function paySalary(int $salaryId)
    {
        $salary = Salary::findOrFail($salaryId);
        $salary->update(['is_paid' => true, 'paid_date' => now()]);
        return response()->json(['message' => 'Salary marked as paid']);
    }

    // Advances & Deductions
    public function createAdvanceDeduction(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|in:advance,deduction',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);
        $ad = AdvanceDeduction::create($request->only([
            'employee_id', 'type', 'amount', 'description', 'date', 'month',
        ]));
        return response()->json($ad, 201);
    }

    public function listAdvancesDeductions(Request $request)
    {
        $query = AdvanceDeduction::query();
        if ($request->filled('employee_id')) { $query->where('employee_id', $request->employee_id); }
        if ($request->filled('month')) { $query->where('month', $request->month); }
        return response()->json($query->orderByDesc('date')->get());
    }
}
