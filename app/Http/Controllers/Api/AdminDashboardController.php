<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminDashboardController extends Controller
{

    // wallet 
  
    
    public function showDriver(Request $request) {
   
        $query = User::role('user')->where('status', 'active');
    

        if ($request->has('name')) {
            $searchTerm = $request->name;
            $query->where(function($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('phone', 'like', '%' . $searchTerm . '%');
            });
        }
    
        $drivers = $query->select('id','driver_id','name', 'phone', 'balance')
                     ->withCount('trips')
                    ->paginate(25);
   
        return response()->json($drivers);
    }
    

    public function topUp(Request $request) {

        $auth = Auth::id();
            $driver = User::where('phone', $request->phone)->first();
    
            $validator = Validator::make($request->all(),[
                'phone' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
    
        if ($driver) {

            $driver->balance = $driver->balance + $request->balance;
            
            $driver->save();

            $transaction = new Transaction();

            $transaction->user_id = $driver->id;
            $transaction->staff_id = $auth;
            $transaction->amount = $request->balance;
            $transaction->income_outcome = 'income';
            $transaction->save();
  
            $driver = $driver->only(['name', 'phone', 'balance']);
    
            
            return response()->json([
                'message' => 'Top up successfully',
                'driver' => $driver
            ]);
        } else {
            return response()->json([
                'message' => 'Driver not found',
            ], 404);
        }
    }
    


    public function pendingList(){

        $drivers = User::role('user')->where('status', 'pending')
                    ->select('id','name', 'phone','address','nrc_no','driving_license','status')
                    ->paginate(25);
    
        
        return response()->json($drivers);
    }

    public function changeActiveStatus(Request $request){

        $validator = Validator::make($request->all(),[
            'id' => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

         $driver = User::find($request->id);

         if($driver){
            $driver->status = 'active';
            $driver->save();

            $driver = $driver->only(['id','name', 'phone','address','nrc_no','driving_license','status']);
    
            
            return response()->json([
                'message' => 'Status change successfully',
                'driver' => $driver
            ]);
         } else {
            return response()->json([
                'message' => 'Driver not found',
            ], 404);
        }
    }


    public function tripsAllHistory(){
        // $trips = Trip::where('status','completed')->latest()->paginate(25);

        // return response()->json($trips);


            $trips = Trip::whereNotIn('status', ['pending', 'accepted', 'canceled'])
            ->latest()
            ->paginate(25);

        $trips->getCollection()->transform(function ($trip) {
            $extra_fee_ids = json_decode($trip->extra_fee_list);
            if (is_array($extra_fee_ids) && count($extra_fee_ids) > 0) {
                $fees = DB::table('fees')->whereIn('id', $extra_fee_ids)->get();
              
            } else {
                $fees = collect([]);
            }

            $extra_remove_ids = json_decode($trip->extra_fee_remove_list);

            // If there are no extra fee ids, return an empty array
            if (is_array($extra_remove_ids) && count($extra_remove_ids) > 0) {
                $feesremove = DB::table('fees')->whereIn('id', $extra_remove_ids)->get();
              
            } else {
                $feesremove = collect([]);
            }

            $driver = User::where('id', $trip->driver_id)
            ->select('id','driver_id','name')
            ->with(['vehicle' => function ($query) {
                $query->select('user_id', 'vehicle_plate_no');
            }])
            ->first();
        
        // vehicle_plate_no ကို driver object ထဲထည့်ပေးမည်
        if ($driver && $driver->vehicle) {
            $driver->vehicle_plate_no = $driver->vehicle->vehicle_plate_no;
            unset($driver->vehicle); // vehicle ကိုဖျက်ပစ်မည်
        }
        
      
      
    if($trip->user_id !== null){
        $user = User::where('id', $trip->user_id)
        ->select('id', 'driver_id as user_id', 'name', 'phone')
        ->first();
    }else{
        $user = $trip->user_id;
    }


            return [
                'id' => $trip->id,
                'user' => $user,
                'distance' => $trip->distance,
                'duration' => $trip->duration,
                'waiting_time' => $trip->waiting_time,
                'normal_fee' => $trip->normal_fee,
                'waiting_fee' => $trip->waiting_fee,
                'extra_fee' => $trip->extra_fee,
                'initial_fee' => $trip->initial_fee,
                'total_cost' => $trip->total_cost,
                'start_lat' => $trip->start_lat,
                'start_lng' => $trip->start_lng,
                'end_lat' => $trip->end_lat,
                'end_lng' => $trip->end_lng,
                'status' => $trip->status,
                'start_address' => $trip->start_address,
                'end_address' => $trip->end_address,
                'driver' => $driver,
                'cartype' => $trip->cartype,
                'start_time' => $trip->start_time,
                'end_time' => $trip->end_time,
                'extra_fee_list' => $fees,
                'extra_fee_remove_list'=>$feesremove,
                'polyline' => json_decode($trip->polyline),
                'commission_fee' => $trip->commission_fee,
                'created_at' => Carbon::parse($trip->created_at)->format('Y-m-d h:i A'),
                'updated_at' => Carbon::parse($trip->updated_at)->format('Y-m-d h:i A'),
            ];
        });

        return response()->json($trips, 200);

    }


    public function driverIdAllTrip(Request $request){
       
        $validator = Validator::make($request->all(),[
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        
        if($request->id <= 0){

            $trips = Trip::whereNotIn('status', ['pending', 'accepted', 'canceled'])
            ->latest()
            ->paginate(25);
            
            
        }else{
            $trips = Trip::where('driver_id',$request->id)
            ->latest()
            ->paginate(25);
        }
    

        $trips->getCollection()->transform(function ($trip) {

        $extra_fee_ids = json_decode($trip->extra_fee_list);
          
        // If there are no extra fee ids, return an empty array
        if (is_array($extra_fee_ids) && count($extra_fee_ids) > 0) {
            $fees = DB::table('fees')->whereIn('id', $extra_fee_ids)->get();
          
        } else {
            $fees = collect([]);
        }

        $extra_remove_ids = json_decode($trip->extra_fee_remove_list);

        // If there are no extra fee ids, return an empty array
        if (is_array($extra_remove_ids) && count($extra_remove_ids) > 0) {
            $feesremove = DB::table('fees')->whereIn('id', $extra_remove_ids)->get();
          
        } else {
            $feesremove = collect([]);
        }


     
        $driver = User::where('id', $trip->driver_id)
        ->select('id','driver_id','name','phone')
        ->with(['vehicle' => function ($query) {
            $query->select('id','user_id', 'vehicle_plate_no','vehicle_model');
        }])
        ->first();
    

    if ($driver && $driver->vehicle) {
        $driver->vehicle_plate_no = $driver->vehicle->vehicle_plate_no;
        unset($driver->vehicle); // vehicle ကိုဖျက်ပစ်မည်
    }

    if($trip->user_id !== null){
        $user = User::where('id', $trip->user_id)
        ->select('id', 'driver_id as user_id', 'name', 'phone')
        ->first();
    }else{
        $user = $trip->user_id;
    }


        return [
            'id' => $trip->id,
            'user' => $user,
            'distance' => $trip->distance,
            'duration' => $trip->duration,
            'waiting_time' => $trip->waiting_time,
            'normal_fee' => $trip->normal_fee,
            'waiting_fee' => $trip->waiting_fee,
            'extra_fee' => $trip->extra_fee,
            'initial_fee' => $trip->initial_fee,
            'total_cost' => $trip->total_cost,
            'start_lat' => $trip->start_lat,
            'start_lng' => $trip->start_lng,
            'end_lat' => $trip->end_lat,
            'end_lng' => $trip->end_lng,
            'status' => $trip->status,
            'start_address' => $trip->start_address,
            'end_address' => $trip->end_address,
            'driver' => $driver,
            'cartype' => $trip->cartype,
            'start_time' => $trip->start_time,
            'end_time' => $trip->end_time,
            'extra_fee_list' => $fees,
            'extra_fee_remove_list'=>$feesremove,
            'polyline' => json_decode($trip->polyline),
            'commission_fee' => $trip->commission_fee,
            'created_at' => Carbon::parse($trip->created_at)->format('Y-m-d h:i A'),
            'updated_at' => Carbon::parse($trip->updated_at)->format('Y-m-d h:i A'),
        ];
    });
        
        return response()->json($trips);
    }

    public function transactionsList(Request $request){

        $validator = Validator::make($request->all(),[
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        if($request->id <= 0){
            $transactions = Transaction::
            where('income_outcome','income')
            ->latest()
            ->paginate(25);
        }else{
            $transactions = Transaction::where('user_id',$request->id)
                        ->where('income_outcome','income')
                        ->latest()
                        ->paginate(25);
        }
        


                        $transactions->getCollection()->transform(function ($transaction) {
                          
                            $transaction->staff_name = User::where('id', $transaction->staff_id)->value('name');
                            $transaction->user_name = User::where('id', $transaction->user_id)->value('name');

                            return $transaction;
                        });
        return response()->json($transactions);
    }


    // public function updateDriverid(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'id' => 'required|exists:users,id', // Check if the user ID exists
    //         'driver_id' => [
    //             'required',
    //             Rule::unique('users')->ignore($request->id) // Check if driver_id is unique, excluding current user
    //         ]
    //     ]);

    //     if($validator->failed()){
    //         return response()->json(['error' => $validator->errors()], 400);
    //     }

    //     $driver = User::find($request->id);

    //     if($driver){
    //         $driver->driver_id = $request->driver_id;
    //         $driver->save();
    
    //         return response()->json('success',200);
    //     }else{
           
    //         return response()->json('Driver Not Found'); 
    //     }
    
    // }

public function updateDriverId(Request $request){
    // Validate the incoming request
    $validator = Validator::make($request->all(),[
        'id' => 'required|exists:users,id',
        'driver_id' => [
            'required',
            Rule::unique('users')->ignore($request->id)
        ]
    ]);

    // Check if validation fails
    if ($validator->fails()) {
          // Get the first validation error message
        //   $errorMessage = $validator->errors()->first();
        //   return response()->json(['message'=>$errorMessage], 400);
        // return response()->json(['error' => $validator->errors()], 400);
        $errorMessages = $validator->errors()->all();
        return response()->json(['message' => $errorMessages], 400);
     

    }

    // Find the user by ID
    $driver = User::findOrFail($request->id);

    // Update the driver_id
    $driver->driver_id = $request->driver_id;
    $driver->save();

    return response()->json('success', 200);
}

}
