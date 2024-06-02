<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CustomNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomNotificationController extends Controller
{
    public function index()
    {
        $notifications = CustomNotification::orderBy('created_at', 'desc')->paginate(5);
        return view('backend.customNoti.index', compact('notifications'));
    }

    public function storeToken(Request $request)
    {
        Auth::user()->update(['device_token' => $request->device_token]);
        return response()->json(['message' => 'Token successfully stored.']);
    }

    public function sendCustomNotification(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
        ]);
        CustomNotification::create([
            'title' => $validatedData['title'],
            'body' => $validatedData['body']
        ]);

        $url = 'https://fcm.googleapis.com/fcm/send';
        $FcmToken = User::whereNotNull('device_token')->pluck('device_token')->all();

        $serverKey = "AAAAFYDzjbw:APA91bHtk8kPufHHYt_EG1HebesEdgWoqEq51Mq0xSE5XBpi_7vBcG7eAAcWUClAiXScaVgb2x56qDCeZ2yb8ln9CwjItVOwt8eYn9Api0ToysWPR5cGhmIymz1Kr3UBrUgjkqV-b3p7";

        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
            ]
        ];
        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
       
        // FCM response
        return redirect()->route('notification.index')->with('success', 'Notification Sent successfully!');
    }
}
