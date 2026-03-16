<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SmsTemplate;
use App\Models\SmsLog;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function index()
    {
        $templates = SmsTemplate::all();
        $logs = SmsLog::orderByDesc('created_at')->paginate(20);
        return view('sms.index', compact('templates', 'logs'));
    }

    public function updateTemplate(Request $request, SmsTemplate $template)
    {
        $template->update($request->only(['template_text', 'is_active']));
        return back()->with('success', 'تم تحديث القالب');
    }

    public function send(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'message' => 'required|string',
        ]);

        SmsLog::create([
            'phone_number' => $request->phone_number,
            'message' => $request->message,
            'event_type' => 'manual',
            'status' => 'sent',
            'customer_name' => $request->customer_name,
        ]);

        return back()->with('success', 'تم إرسال الرسالة');
    }
}
