<?php

namespace App\Http\Controllers\Web;

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
        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        return view('groups.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:groups,name']);
        Group::create($request->only(['name', 'description']));
        return redirect()->route('groups.index')->with('success', 'تم إنشاء المجموعة');
    }

    public function show(Group $group)
    {
        $group->load('members.customer', 'centralMeters');
        $members = $group->members;
        $centralMeters = $group->centralMeters;
        $customers = Customer::where('is_archived', false)->get();
        return view('groups.show', compact('group', 'members', 'centralMeters', 'customers'));
    }

    public function addMember(Request $request, Group $group)
    {
        $request->validate(['customer_id' => 'required|exists:customers,id']);
        $existing = GroupMember::where('group_id', $group->id)->where('customer_id', $request->customer_id)->first();
        if ($existing) {
            return back()->withErrors(['customer_id' => 'العميل موجود بالفعل في المجموعة']);
        }
        GroupMember::create(['group_id' => $group->id, 'customer_id' => $request->customer_id]);
        return back()->with('success', 'تم إضافة العضو');
    }

    public function removeMember(Group $group, int $customerId)
    {
        GroupMember::where('group_id', $group->id)->where('customer_id', $customerId)->delete();
        return back()->with('success', 'تم إزالة العضو');
    }

    public function calculateLoss(Request $request, Group $group)
    {
        $request->validate(['month' => 'required|string|size:7']);
        $result = ElectricityLossService::calculateLoss($group->id, $request->month);
        return view('groups.loss', compact('result', 'group'));
    }
}
