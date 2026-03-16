<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Salary;
use App\Models\AdvanceDeduction;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::paginate(20);
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Employee::create($request->only([
            'name', 'phone', 'email', 'position', 'department',
            'base_salary', 'hire_date', 'national_id', 'address',
        ]));
        return redirect()->route('employees.index')->with('success', 'تم إضافة الموظف');
    }

    public function show(Employee $employee)
    {
        $attendances = Attendance::where('employee_id', $employee->id)->orderByDesc('date')->limit(30)->get();
        $salaries = Salary::where('employee_id', $employee->id)->orderByDesc('month')->get();
        return view('employees.show', compact('employee', 'attendances', 'salaries'));
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $employee->update($request->only([
            'name', 'phone', 'email', 'position', 'department',
            'base_salary', 'is_active', 'national_id', 'address',
        ]));
        return redirect()->route('employees.index')->with('success', 'تم تحديث الموظف');
    }
}
