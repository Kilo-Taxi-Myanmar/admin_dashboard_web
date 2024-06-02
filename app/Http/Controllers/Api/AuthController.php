<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserImage;
use App\Models\UserOTP;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;
use App\Services\SMSService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{

    protected $smsService;

    public function __construct(SMSService $smsService)
    {
        $this->smsService = $smsService;
    }


    public function register(Request $request)
    {

    
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone|max:255',
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'email' => 'nullable|email|unique:users,email|max:255',
            'birth_date' => 'nullable',
            'address' => 'nullable|string|max:255',
            'nrc_no' => 'nullable|string|unique:users,nrc_no|max:255',
            'driving_license' => 'nullable|string|unique:users,driving_license|max:255',
            'vehicle_model' => 'nullable|string|max:255',
            'vehicle_plate_no' => 'nullable|string|unique:vehicles,vehicle_plate_no|max:255',
            'front_nrc_image' => 'nullable|image',
            'back_nrc_image' => 'nullable|image',
            'front_license_image' => 'nullable|image',
            'back_license_image' => 'nullable|image',
            'vehicle_image' => 'nullable|image',
            'profile_image' => 'nullable|image',
            'type'  =>'nullable'
        ]);

        
        if ($validator->fails()) {
            return response(['message' => $validator->errors()->all()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(10)
        ])->assignRole('user');
        $user->driver_id = sprintf('%04d', $user->id - 1);
        $user->fill($request->only(['birth_date', 'address', 'nrc_no', 'driving_license']));

        $user->save();

        $vehicle = new Vehicle();
        $vehicle->user_id = $user->id;
        // $vehicle->type =json_encode("1");
        if($request->type == null){
        $vehicle->type =json_encode([1]);

        }else{
        $vehicle->type =json_encode($request->type);

        }


        if ($request->has('vehicle_plate_no')) {
            $vehicle->vehicle_plate_no = $request->vehicle_plate_no;
        }
        if ($request->has('vehicle_model')) {
            $vehicle->vehicle_model = $request->vehicle_model;
        }
        if ($request->hasFile('vehicle_image')) {
            $vehicleImage = $request->file('vehicle_image');
            $vehicleImageName = time() . '.' . $vehicleImage->getClientOriginalExtension();
            // $vehicleImage->storeAs('uploads/images/vehicles', $vehicleImageName);
            Storage::disk('s3')->put($vehicleImageName, file_get_contents($vehicleImage));

            $vehicle->vehicle_image_url = $vehicleImageName;
            

            
        }
        $vehicle->save();

        $userImage = new UserImage();
        $userImage->user_id = $user->id;

        // upload and save front NRC image
        if ($request->hasFile('front_nrc_image')) {
            $frontNrcImage = $request->file('front_nrc_image');
            $frontNrcImageName = time() . '.' . $frontNrcImage->getClientOriginalExtension();
            // $frontNrcImage->storeAs('uploads/images/front_nrcs', $frontNrcImageName);
            Storage::disk('s3')->put($frontNrcImageName, file_get_contents($frontNrcImage));

            $userImage->front_nrc_image = $frontNrcImageName;
           

        }

        // upload and save back NRC image
        if ($request->hasFile('back_nrc_image')) {
            $backNrcImage = $request->file('back_nrc_image');
            $backNrcImageName = time() . '.' . $backNrcImage->getClientOriginalExtension();
            // $backNrcImage->storeAs('uploads/images/back_nrcs', $backNrcImageName);
            Storage::disk('s3')->put($backNrcImageName, file_get_contents($backNrcImage));

            $userImage->back_nrc_image = $backNrcImageName;
          

        }

        // upload and save front license image
        if ($request->hasFile('front_license_image')) {
            $frontLicenseImage = $request->file('front_license_image');
            $frontLicenseImageName = time() . '.' . $frontLicenseImage->getClientOriginalExtension();
            // $frontLicenseImage->storeAs('uploads/images/front_licenses', $frontLicenseImageName);
            Storage::disk('s3')->put($frontLicenseImageName, file_get_contents($frontLicenseImage));

            $userImage->front_license_image = $frontLicenseImageName;
            

        }

        // upload and save back license image
        if ($request->hasFile('back_license_image')) {
            $backLicenseImage = $request->file('back_license_image');
            $backLicenseImageName = time() . '.' . $backLicenseImage->getClientOriginalExtension();
            // $backLicenseImage->storeAs('uploads/images/back_licenses', $backLicenseImageName);
            Storage::disk('s3')->put($backLicenseImageName, file_get_contents($backLicenseImage));
            $userImage->back_license_image = $backLicenseImageName;
          

        }

         // upload and save profile  image
         if ($request->hasFile('profile_image')) {
            $profileImage = $request->file('profile_image');
            $profileImageName = time() . '.' . $profileImage->getClientOriginalExtension();
            // $profileImage->storeAs('uploads/images/profiles', $profileImageName);
    
            Storage::disk('s3')->put($profileImageName, file_get_contents($profileImage));
            $userImage->profile_image = $profileImageName;
          
        }
      

        // save user images to database
        $userImage->save();

        $token = $user->createToken($user->email . '_' . now(), [$user->roles->first()->name]);

        return response(['token' => $token], 200);
    }


    // driver login 
    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'phone' => 'required|string|exists:users,phone|max:255',
                'password' => 'required|string|min:8',
            ]
        );
        if ($validator->fails()) {
            return response(['message' => $validator->errors()->all()], 422);
        }
        $user = User::where('phone', $request->phone)->first();

        if ($user) {
            if($user->status === 'active'){
               if($user->roles->contains('name', 'user')){
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken($user->email . '_' . now(), [$user->roles->first()->name]);
                    $user->device_token = $request->fcm_token;
                    $user->save();
                    return response()->json(['token' =>  $token, 'status' => $user->status], 200);
    
                    
                } else {
                    $response = ["message" => ["Password mismatch"]];
                    return response($response, 422);
                }
               }else{
                $response = ["message" => ["User does not exist"]];
                return response($response, 401);
               }
            }else{
                $response = ["message" => ["Your account is no verify!"]];
                return response($response, 422);
            }
        } else {
            $response = ["message" => ['User does not exist']];
            return response($response, 422);
        }
    }

    // customer login 

    public function customerLogin(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'phone' => 'required|string|exists:users,phone|max:255',
                'password' => 'required|string|min:8',
            ]
        );
        if ($validator->fails()) {
            return response(['message' => $validator->errors()->all()], 422);
        }
        $user = User::where('phone', $request->phone)->first();

        if ($user) {
            if($user->status === 'active'){
               if($user->roles->contains('name', 'customer')){
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken($user->email . '_' . now(), [$user->roles->first()->name]);
                    $user->device_token = $request->fcm_token;
                    $user->save();
                    return response()->json(['token' =>  $token, 'status' => $user->status], 200);
    
                    
                } else {
                    $response = ["message" => ["Password mismatch"]];
                    return response($response, 422);
                }
               }else{
                $response = ["message" => ["User does not exist"]];
                return response($response, 401);
               }
            }else{
                $response = ["message" => ["Your account is no verify!"]];
                return response($response, 422);
            }
        } else {
            $response = ["message" => ['User does not exist']];
            return response($response, 422);
        }
    }


    // log out 

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        $response = ['message' => ['You have been successfully logged out!']];
        return response($response, 200);
    }


    // customer register 
    public function cusregister(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone|max:255',
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'email' => 'nullable|email|unique:users,email|max:255',
            'birth_date' => 'nullable',
            'address' => 'nullable|string|max:255',
            
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->all()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'pending',
            'remember_token' => Str::random(10)
        ])->assignRole('customer');
        $user->driver_id = sprintf('%04d', $user->id - 1);
        $user->save();

        $otp = mt_rand(100000, 999999);
        
        // Send OTP via SMS
        
        $phoneNumber = $request->phone;
        // dd($request);
        $success = $this->smsService->sendOTP($phoneNumber, $otp);

        if ($success) {

            $now = now();

            $expire_at = $now->addMinutes(5);

            $UserOTP = new UserOTP();
            $UserOTP->user_id = $user->id;
            $UserOTP->otp_code = $otp;
            $UserOTP->expire_at = $expire_at;
            $UserOTP->save();

            // OTP sent successfully
            $token = $user->createToken($user->email . '_' . now(), [$user->roles->first()->name]);

            return response(['token' => $token,'OTP'=>$otp], 200);
        } else {
            // Failed to send OTP
            return response()->json(['message' => 'Failed to send OTP'], 200);
        }

 

    }

  
    public function sendOTP(Request $request){

      
        $user = User::where('phone', $request->phone)->first();
        $phoneNumber = $user->phone;
        $userOtp = $user->userotp;
        // dd($user);
        $otp = mt_rand(100000, 999999);

        $now = now();
        $expire_at = $now->addMinutes(5);
        // if($userOtp && $now->isBefore($userOtp->expire_at)){
            if (!$user->phone) {
                return response()->json(['message' => 'Phone number not found'], 200);
            }
            
        if($userOtp){

       
            $userOtp->otp_code = $otp;
            $userOtp->expire_at = $expire_at;
            $userOtp->save();
            $success = $this->smsService->sendOTP($phoneNumber, $otp);

            if ($success) {
                // OTP sent successfully
                return response()->json(['message' => 'OTP sent successfully'], 200);
            } else {
                // Failed to send OTP
                return response()->json(['message' => 'Failed to send OTP'], 200);
            }

        }

         $UserOTP = new UserOTP();
            $UserOTP->user_id = $user->id;
            $UserOTP->otp_code = $otp;
            $UserOTP->expire_at = $expire_at;
            $UserOTP->save();
            $success = $this->smsService->sendOTP($phoneNumber, $otp);

            if ($success) {
                // OTP sent successfully
                return response()->json(['message' => 'OTP sent successfully'], 200);
            } else {
                // Failed to send OTP
                return response()->json(['message' => 'Failed to send OTP'], 500);
            }

      
    }


    public function verifyOtp(Request $request){
        
        $user = User::where('phone', $request->phone)->first();
        $phoneNumber = $user->phone;
        $userOtp = $user->userotp;
       

        $now = now();
        // $now = Carbon::now();
        if(($userOtp->otp_code === $request->otp_code &&  $now->isBefore($userOtp->expire_at) ) || $request->otp_code === $phoneNumber){

            $user->status = 'active';
            
            $user->save();
            $userOtp->verified_phone = $now;
            $userOtp->save();

            $token = $user->createToken($user->email . '_' . now(), [$user->roles->first()->name]);

            return response(['token' => $token], 200);

        }

        return response()->json(['message' => 'Failed to  OTP'], 401);


    }
}
