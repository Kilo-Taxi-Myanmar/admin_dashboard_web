<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserOTP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\SMSService;
class ForgetPasswordController extends Controller
{

    protected $smsService;

    public function __construct(SMSService $smsService)
    {
        $this->smsService = $smsService;
    }


    public function forgetPassword(Request $request){

            // Validate request
            $validator = Validator::make($request->all(), [
                'phone' => 'required'
            ]);
        
            if ($validator->fails()) {
                return response(['message' => $validator->errors()->all()], 422);
            }
        
            // Attempt to find user with provided phone number
            $user = User::where('phone', $request->phone)->first();

            if (!$user) {
                // No user found
                return response()->json(['message' => 'The phone number does not exist'], 404);
            }

             $userOtp = $user->userotp;

            
        
            // Generate OTP
            $otp = mt_rand(100000, 999999);
        
            // Assuming smsService is injected via the service container
            $success = $this->smsService->sendOTP($request->phone, $otp); 
            
            

            $expire_at = now()->addMinutes(5);
        
            if ($success) {

                if($userOtp){
                    
                    $userOtp->otp_code = $otp;
                    $userOtp->expire_at = $expire_at;
                    $userOtp->save();
            }else{
                     // Create UserOTP entry
                
                $UserOTP = new UserOTP([
                    'user_id' => $user->id,                   
                    'otp_code' => $otp,
                    'expire_at' => $expire_at
                ]);
                $UserOTP->save();

            }
    
               
        
                // Create token assuming user has at least one role
                // $token = null;
                // if ($user->roles->isNotEmpty()) {
                //     $token = $user->createToken($user->email . '_' . now(), [$user->roles->first()->name])->plainTextToken;
                // }
        
                // Successful OTP generation and sending
                return response()->json(['OTP' => $otp], 200);
            } else {
                // Failed to send OTP
                return response()->json(['message' => 'Failed to send OTP'], 503);
            }
        
    }
}
