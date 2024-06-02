<?php

namespace App\Http\Controllers\Api;

use App\Events\TopUpRequestNotiEvent;
use App\Http\Controllers\Controller;
use App\Models\CustomNotification;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::all();
        return response()->json($notifications, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'service' => 'required|string',
            'account_name' => 'required|string',
            'phone' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $notification = Notification::create([
            'user_id' => $request->user_id,
            'service' => $request->service,
            'account_name' => $request->account_name,
            'phone' => $request->phone,
            'amount' => $request->amount,
        ]);

        if ($request->hasFile('screenshot')) {
            $screenshot = $request->file('screenshot');
            $screenshotName = $notification->phone . '_' .  time() . '.' . $screenshot->getClientOriginalExtension();
            $screenshot->storeAs('uploads/images/screenshots/', $screenshotName);
            $notification->screenshot = $screenshotName;
            $notification->save();
        }
        $message = User::find($request->user_id)->name . ' add ' . $notification->amount . 'MMK';
        broadcast(new TopUpRequestNotiEvent($message));

        return response()->json([['message' => 'success'], 200]);
    }

    public function custom_notification()
    {
        $notifications = CustomNotification::orderBy('created_at', 'desc')->get();
        return response()->json($notifications, 200);
    }
}
