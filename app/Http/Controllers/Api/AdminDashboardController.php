<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
    
        $drivers = $query->select('name', 'phone', 'balance')
                     ->withCount('trips')
                    ->paginate(25);
   
        return response()->json($drivers);
    }
    

    public function topUp(Request $request) {
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

            return [
                'id' => $trip->id,
                'user_id' => $trip->user_id,
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
                'driver_id' => $trip->driver_id,
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
        
        $trips = Trip::where('driver_id',$request->id)
        ->latest()
        ->paginate(25);

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

        return [
            'id' => $trip->id,
            'user_id' => $trip->user_id,
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
            'driver_id' => $trip->driver_id,
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
        $transactions = Transaction::where('user_id',$request->id)
                        ->where('income_outcome','income')
                        ->latest()
                        ->paginate(25);


                        $transactions->getCollection()->transform(function ($transaction) {
                          
                            $transaction->staff_name = User::where('id', $transaction->staff_id)->value('name');
                            return $transaction;
                        });
        return response()->json($transactions);
    }
}
