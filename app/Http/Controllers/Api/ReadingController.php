<?php

namespace App\Http\Controllers\Api;

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
        $query = MeterReading::query();
        if ($request->filled('month')) { $query->where('month', $request->month); }
        if ($request->filled('customer_id')) { $query->where('customer_id', $request->customer_id); }
        if ($request->filled('meter_id')) { $query->where('meter_id', $request->meter_id); }

        return response()->json(
            $query->orderByDesc('reading_date')
                  ->skip($request->input('skip', 0))
                  ->take($request->input('limit', 100))
                  ->get()
        );
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
            return response()->json(['message' => 'Reading already exists for this meter and month'], 400);
        }

        $previous = ReadingService::getPreviousReading($request->meter_id, $request->month);
        $isOpening = $request->boolean('is_opening', false);

        if ($request->reading_value < $previous && !$isOpening) {
            return response()->json([
                'message' => "Reading value ({$request->reading_value}) cannot be less than previous reading ({$previous})"
            ], 400);
        }

        $consumption = $isOpening ? 0.0 : ($request->reading_value - $previous);

        $reading = MeterReading::create([
            'meter_id' => $request->meter_id,
            'customer_id' => $request->customer_id,
            'reading_value' => $request->reading_value,
            'previous_reading' => $previous,
            'consumption' => $consumption,
            'reading_date' => $request->reading_date,
            'month' => $request->month,
            'is_opening' => $isOpening,
            'notes' => $request->notes,
            'read_by' => $request->user()->id,
        ]);

        return response()->json($reading, 201);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'month' => 'required|string|size:7',
        ]);

        // Placeholder for Excel import - requires maatwebsite/excel package
        return response()->json(['message' => 'Import feature available with maatwebsite/excel package'], 200);
    }
}
