<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\CentralMeter;
use App\Models\CentralMeterReading;
use App\Models\Customer;
use App\Services\ElectricityLossService;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::withCount('members')->get();
        return response()->json($groups->map(function ($g) {
            $data = $g->toArray();
            $data['member_count'] = $g->members_count;
            return $data;
        }));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:groups,name']);
        $group = Group::create($request->only(['name', 'description']));
        $data = $group->toArray();
        $data['member_count'] = 0;
        return response()->json($data, 201);
    }

    public function show(int $id)
    {
        $group = Group::withCount('members')->findOrFail($id);
        $data = $group->toArray();
        $data['member_count'] = $group->members_count;
        return response()->json($data);
    }

    public function update(Request $request, int $id)
    {
        $group = Group::findOrFail($id);
        $group->update($request->only(['name', 'description']));
        $data = $group->toArray();
        $data['member_count'] = GroupMember::where('group_id', $id)->count();
        return response()->json($data);
    }

    public function addMember(Request $request, int $groupId)
    {
        $request->validate(['customer_id' => 'required|exists:customers,id']);
        Group::findOrFail($groupId);
        $customer = Customer::findOrFail($request->customer_id);

        $existing = GroupMember::where('group_id', $groupId)
            ->where('customer_id', $request->customer_id)->first();
        if ($existing) {
            return response()->json(['message' => 'Customer already in group'], 400);
        }

        $member = GroupMember::create(['group_id' => $groupId, 'customer_id' => $request->customer_id]);
        $data = $member->toArray();
        $data['customer_name'] = $customer->name;
        return response()->json($data, 201);
    }

    public function listMembers(int $groupId)
    {
        $members = GroupMember::where('group_id', $groupId)->with('customer')->get();
        return response()->json($members->map(function ($m) {
            $data = $m->toArray();
            $data['customer_name'] = $m->customer?->name;
            return $data;
        }));
    }

    public function removeMember(int $groupId, int $customerId)
    {
        $member = GroupMember::where('group_id', $groupId)
            ->where('customer_id', $customerId)->firstOrFail();
        $member->delete();
        return response()->json(['message' => 'Member removed']);
    }

    // Central Meters
    public function createCentralMeter(Request $request)
    {
        $request->validate([
            'meter_number' => 'required|string|unique:central_meters,meter_number',
            'group_id' => 'required|exists:groups,id',
        ]);
        $meter = CentralMeter::create($request->only(['meter_number', 'group_id', 'location']));
        return response()->json($meter, 201);
    }

    public function listCentralMeters()
    {
        return response()->json(CentralMeter::all());
    }

    public function createCentralMeterReading(Request $request)
    {
        $request->validate([
            'central_meter_id' => 'required|exists:central_meters,id',
            'reading_value' => 'required|numeric',
            'reading_date' => 'required|date',
            'month' => 'required|string|size:7',
        ]);

        $last = CentralMeterReading::where('central_meter_id', $request->central_meter_id)
            ->where('month', '<', $request->month)
            ->orderByDesc('month')->first();

        $previous = $last ? $last->reading_value : 0.0;
        $consumption = $request->reading_value - $previous;

        $reading = CentralMeterReading::create([
            'central_meter_id' => $request->central_meter_id,
            'reading_value' => $request->reading_value,
            'previous_reading' => $previous,
            'consumption' => $consumption,
            'month' => $request->month,
            'reading_date' => $request->reading_date,
        ]);

        return response()->json($reading, 201);
    }

    public function calculateLoss(Request $request, int $groupId)
    {
        $request->validate(['month' => 'required|string|size:7']);
        $result = ElectricityLossService::calculateLoss($groupId, $request->month);
        return response()->json($result);
    }
}
