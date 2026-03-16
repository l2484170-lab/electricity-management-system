<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MeterReading;
use App\Models\Meter;
use App\Models\Customer;
use App\Services\ReadingService;
use Illuminate\Http\Request;

class ReadingController extends Controller
{
    public function index(Request $request)
    {
        $query = MeterReading::with(['meter', 'customer']);
        if ($request->filled('month')) { $query->where('month', $request->month); }
        if ($request->filled('customer_id')) { $query->where('customer_id', $request->customer_id); }
        $readings = $query->orderByDesc('reading_date')->paginate(20);
        return view('readings.index', compact('readings'));
    }

    public function create()
    {
        $meters = Meter::with('customer')->where('status', 'active')->get();
        $customers = Customer::where('is_archived', false)->where('is_active', true)->get();
        return view('readings.create', compact('meters', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'meter_id' => 'required|exists:meters,id',
            'customer_id' => 'required|exists:customers,id',
            'reading_value' => 'required|numeric',
            'reading_date' => 'required|date',
            'month' => 'required|string|size:7',
        ]);

        $existing = MeterReading::where('meter_id', $request->meter_id)
            ->where('month', $request->month)->first();
        if ($existing) {
            return back()->withErrors(['month' => 'توجد قراءة بالفعل لهذا العداد وهذا الشهر'])->withInput();
        }

        $previous = ReadingService::getPreviousReading($request->meter_id, $request->month);
        $isOpening = $request->boolean('is_opening', false);

        if ($request->reading_value < $previous && !$isOpening) {
            return back()->withErrors(['reading_value' => "القيمة ({$request->reading_value}) لا يمكن أن تكون أقل من السابقة ({$previous})"])->withInput();
        }

        $consumption = $isOpening ? 0.0 : ($request->reading_value - $previous);

        MeterReading::create([
            'meter_id' => $request->meter_id,
            'customer_id' => $request->customer_id,
            'reading_value' => $request->reading_value,
            'previous_reading' => $previous,
            'consumption' => $consumption,
            'reading_date' => $request->reading_date,
            'month' => $request->month,
            'is_opening' => $isOpening,
            'notes' => $request->notes,
            'read_by' => auth()->id(),
        ]);

        return redirect()->route('readings.index')->with('success', 'تم تسجيل القراءة بنجاح');
    }
}
