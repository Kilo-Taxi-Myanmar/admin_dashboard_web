<?php

namespace App\Http\Controllers\Api;

use App\Events\DriverEvent;
use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\User;
use App\Models\UserImage;
use App\Models\Vehicle;
use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    

    public function profile()
    {
        $user = Auth::user();
        $url = 'https://s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . env('AWS_BUCKET') . '/';
        $userImage = $user->userImage;
        $vehicle = $user->vehicle;
        $transaction = $user->transactions;
        $trip = $user->trip;
        $notification = $user->notifications;

        return response()->json(['user' => $user,'url'=>$url, 'success' => true], 200);
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8| max:255',
            'email' => 'nullable|email|max:255',
            'birth_date' => 'nullable',
            'address' => 'nullable|string|max:255',
            'nrc_no' => 'nullable|string|max:255',
            'driving_license' => 'nullable|string|max:255',
            'vehicle_model' => 'nullable|string|max:255',
            'vehicle_plate_no' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image',
            'front_nrc_image' => 'nullable|image',
            'back_nrc_image' => 'nullable|image',
            'front_license_image' => 'nullable|image',
            'back_license_image' => 'nullable|image',
            'vehicle_image' => 'nullable|image'
        ]);

        $user = Auth::user();
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 422);
        }
        $user->fill($request->only(['name', 'phone', 'email', 'birth_date', 'address', 'nrc_no', 'driving_license']));

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        if ($user->userImage) {
            $userImage = UserImage::where('user_id', $user->id)->first();
        } else {
            $userImage = new UserImage();
            $userImage->user_id = $user->id;
        }
        // update profile image
        if ($request->hasFile('profile_image')) {
            
            if (Storage::disk('s3')->exists($userImage->profile_image)) {
           
                Storage::disk('s3')->delete($userImage->profile_image);
            }
            $profileImage = $request->file('profile_image');
            $profileImageName = time() . '_' . $user->nrc_no . '.' . $profileImage->getClientOriginalExtension();
            Storage::disk('s3')->put($profileImageName, file_get_contents($profileImage));
            $userImage->profile_image = $profileImageName;
            $userImage->save();
        }

        // // update front NRC image
        if ($request->hasFile('front_nrc_image')) {
           
            if (Storage::disk('s3')->exists($userImage->front_nrc_image)) {
           
                Storage::disk('s3')->delete($userImage->front_nrc_image);
            }
            $frontNrcImage = $request->file('front_nrc_image');
            $frontNrcImageName = time() . '.' . $frontNrcImage->getClientOriginalExtension();
            Storage::disk('s3')->put($frontNrcImageName, file_get_contents($frontNrcImage));
            $userImage->front_nrc_image = $frontNrcImageName;
            $userImage->save();
        }

        // // update back NRC image
        if ($request->hasFile('back_nrc_image')) {
           
            if (Storage::disk('s3')->exists($userImage->back_nrc_image)) {
           
                Storage::disk('s3')->delete($userImage->back_nrc_image);
            }
            $backNrcImage = $request->file('back_nrc_image');
            $backNrcImageName = time() . '.' . $backNrcImage->getClientOriginalExtension();
            Storage::disk('s3')->put($backNrcImageName, file_get_contents($backNrcImage));
            $userImage->back_nrc_image = $backNrcImageName;
            $userImage->save();
        }

        // // update front license image
        if ($request->hasFile('front_license_image')) {
           
            if (Storage::disk('s3')->exists($userImage->front_license_image)) {
           
                Storage::disk('s3')->delete($userImage->front_license_image);
            }
            $backNrcImage = $request->file('front_license_image');
            $backNrcImageName = time() . '.' . $backNrcImage->getClientOriginalExtension();
            Storage::disk('s3')->put($backNrcImageName, file_get_contents($backNrcImage));
            $userImage->front_license_image = $backNrcImageName;
            $userImage->save();
        }
        // // update back license image
        if ($request->hasFile('back_license_image')) {
            
            if (Storage::disk('s3')->exists($userImage->back_license_image)) {
           
                Storage::disk('s3')->delete($userImage->back_license_image);
            }
            $backLicenseImage = $request->file('back_license_image');
            $backLicenseImageName = time() . '.' . $backLicenseImage->getClientOriginalExtension();
            Storage::disk('s3')->put($backLicenseImageName, file_get_contents($backLicenseImage));
          
            $userImage->back_license_image = $backLicenseImageName;
            $userImage->save();
        }

        // Vehicle Data update
        $validator = Validator::make($request->all(), [
            'vehicle_plate_no' => 'nullable|string|max:255',
            'vehicle_model' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 422);
        }

        if ($user->vehicle) {
            $vehicle = Vehicle::where('user_id', $user->id)->first();
        } else {
            $vehicle = new Vehicle();
            $vehicle->user_id = $user->id;
        }

        if ($request->has('vehicle_plate_no')) {
            $vehicle->vehicle_plate_no = $request->vehicle_plate_no;
        }
        if ($request->has('vehicle_model')) {
            $vehicle->vehicle_model = $request->vehicle_model;
        }
        if ($request->hasFile('vehicle_image')) {

            $oldImage = $vehicle->vehicle_image_url; //get old image by ID
            
            if (Storage::disk('s3')->exists($oldImage)) {
           
                Storage::disk('s3')->delete($oldImage);
            }
            $vehicleImage = $request->file('vehicle_image');
            $vehicleImageName = time() . '.' . $vehicleImage->getClientOriginalExtension();
            // $vehicleImage->storeAs('uploads/images/vehicles', $vehicleImageName);
            Storage::disk('s3')->put($vehicleImageName, file_get_contents($vehicleImage));

            $vehicle->vehicle_image_url = $vehicleImageName;
        }
        $vehicle->save();
        return response()->json(['user' => $user, 'status' => 'User updated successfully', 'success' => true], 200);
    }

    public function destroy(User $user)
    {
        if ($user->has('userImage')) {
            $userImage = $user->userImage;
            if (Storage::disk('s3')->exists($userImage->profile_image)) {
           
                Storage::disk('s3')->delete($userImage->profile_image);
            }
           
            if (Storage::disk('s3')->exists($userImage->front_nrc_image)) {
           
                Storage::disk('s3')->delete($userImage->front_nrc_image);
            }
            
            if (Storage::disk('s3')->exists($userImage->back_nrc_image)) {
           
                Storage::disk('s3')->delete($userImage->back_nrc_image);
            }
            
            if (Storage::disk('s3')->exists($userImage->front_license_image)) {
           
                Storage::disk('s3')->delete($userImage->front_license_image);
            }
          
                   
            if (Storage::disk('s3')->exists($userImage->back_license_image)) {
           
                Storage::disk('s3')->delete($userImage->back_license_image);
            }
        }

        if (isset($user->vehicle)) {
            $vehicle = Vehicle::find($user->vehicle->id);
            
            if (Storage::disk('s3')->exists($vehicle->vehicle_image_url)) {
           
                Storage::disk('s3')->delete($vehicle->vehicle_image_url);
            }
            $vehicle->delete();
        }
        $user->tokens()->delete();
        $user->delete();
        return response()->json(['status' => 'User deleted successfully', 'success' => true], 200);
    }

    public function search(Request $request)
    {
        $key = $request->input('key');

        $users = User::role('user')->where('name', 'LIKE', "%$key%")
            ->orWhere('email', 'LIKE', "%$key%")
            ->orWhere('phone', 'LIKE', "%$key%")
            ->orWhere('driver_id', '=', $key)
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    public function userTrip()
    {
        $user = Auth::user();
       
        // $trips = Trip::where('driver_id',$user->id)
        //                 ->whereNot('status','pending')
        //                 ->whereNot('status','accepted')
        //                 ->whereNot('status','canceled')
        //                 ->latest()
        //                 ->get();
        
        $trips = Trip::where('driver_id', $user->id)
            ->whereNotIn('status', ['pending', 'accepted', 'canceled'])
            ->latest()
            ->get()
            ->map(function ($trip) {
                // Decode the JSON array stored in extra_fee_list
                    $extra_fee_ids = json_decode($trip->extra_fee_list);

                    // Fetch fee details based on decoded IDs
                    $fees = collect($extra_fee_ids)->map(function ($id) {
                        return DB::table('fees')->where('id', $id)->first();
                    });
                // $extraFeeList = json_decode($trip->extra_fee_list, true);
                // $extraFees = Fee::whereIn('id', $extraFeeList)->get();
                return [
                    'id' => $trip->id,
                    'user_id'=>$trip->user_id,
                    'distance'=> $trip->distance,
                    'duration'=> $trip->duration,
                    'waiting_time'=> $trip->waiting_time,
                    'normal_fee'=>$trip->normal_fee,
                    'waiting_fee'=>$trip->waiting_fee,
                    'extra_fee'=>$trip->extra_fee,
                    'initial_fee'=>$trip->initial_fee,
                    'total_cost'=>$trip->total_cost,
                    'start_lat'=>$trip->start_lat,
                    'start_lng'=>$trip->start_lng,
                    'end_lat'=>$trip->end_lat,
                    'end_lng'=>$trip->end_lng,
                    'status' => $trip->status,
                    'start_address'=>$trip->start_address,
                    'end_address'=>$trip->end_address,
                    'driver_id' => $trip->driver_id,
                    'cartype'=>$trip->cartype,
                    'start_time'=>$trip->start_time,
                    'end_time' => $trip->end_time,
                    'extra_fee_list'=>$fees->toArray(),
                    // 'extra_fee_list'=>$extraFees,

                    'created_at' => Carbon::parse($trip->created_at)->format('Y-m-d h:i A'),
                    'updated_at' => Carbon::parse($trip->updated_at)->format('Y-m-d h:i A'),
                   
                ];
            });
        return response()->json($trips, 200);
    }

    public function disable($user_id){
        $user = User::find($user_id);
        $user->status = 'pending';
        $user->update();
        return response()->json(['status' => 'User deleted successfully', 'success' => true], 200);


    }


    public function cusupdate(Request $request)
    {

        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255|unique:users,phone,'.$user->id,
            'password' => 'nullable|string|min:8| max:255',
            'email' => 'nullable|email|max:255',
            'birth_date' => 'nullable',
            'address' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image',
            
        ]);

       
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 422);
        }
        $user->fill($request->only(['name', 'phone', 'email', 'birth_date', 'address']));

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        
        $user->save();

        if ($user->userImage) {
            $userImage = UserImage::where('user_id', $user->id)->first();
        } else {
            $userImage = new UserImage();
            $userImage->user_id = $user->id;
        }
        // update profile image
        if ($request->hasFile('profile_image')) {
            // if (Storage::exists('uploads/images/profiles/' . $userImage->profile_image)) {
            //     Storage::delete('uploads/images/profiles/' . $userImage->profile_image); //delete old image
            // }

            if (Storage::disk('s3')->exists($userImage->profile_image)) {
           
                Storage::disk('s3')->delete($userImage->profile_image);
            }
            $profileImage = $request->file('profile_image');
            $profileImageName = time() . '_' . $user->nrc_no . '.' . $profileImage->getClientOriginalExtension();
            // $profileImage->storeAs('uploads/images/profiles', $profileImageName);
            Storage::disk('s3')->put($profileImageName, file_get_contents($profileImage));
            $userImage->profile_image = $profileImageName;
            $userImage->save();
        }

       
        return response()->json(['user' => $user, 'status' => 'User updated successfully', 'success' => true], 200);
    }


    
}
