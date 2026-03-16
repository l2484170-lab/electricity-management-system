<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SmsTemplate;
use App\Models\SmsLog;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function listTemplates()
    {
        return response()->json(SmsTemplate::all());
    }

    public function createTemplate(Request $request)
    {
        $request->validate([
            'event_type' => 'required|in:invoice_created,payment_received,payment_overdue|unique:sms_templates,event_type',
            'template_text' => 'required|string',
        ]);
        $template = SmsTemplate::create($request->only(['event_type', 'template_text', 'is_active']));
        return response()->json($template, 201);
    }

    public function updateTemplate(Request $request, int $id)
    {
        $template = SmsTemplate::findOrFail($id);
        $template->update($request->only(['template_text', 'is_active']));
        return response()->json($template);
    }

    public function send(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'message' => 'required|string',
        ]);

        $smsLog = SmsLog::create([
            'phone_number' => $request->phone_number,
            'message' => $request->message,
            'event_type' => 'manual',
            'status' => 'sent',
            'customer_name' => $request->customer_name,
        ]);

        return response()->json(['message' => 'SMS sent', 'id' => $smsLog->id]);
    }

    public function logs(Request $request)
    {
        return response()->json(
            SmsLog::orderByDesc('created_at')
                   ->skip($request->input('skip', 0))
                   ->take($request->input('limit', 100))
                   ->get()
        );
    }
}
