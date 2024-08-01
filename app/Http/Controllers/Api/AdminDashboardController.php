<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminDashboardController extends Controller
{

    // wallet 
    public function showDriver(Request $request) {
        // Query ကိုစတင်တည်ဆောက်သည်
        $query = User::role('user')->where('status', 'active');
    
        // Name filter ထည့်မည်
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
    
        // Phone filter ထည့်မည်
        if ($request->has('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }
    
        // Paginate နဲ့ရှာဖွေမှုလုပ်ပြီး select လုပ်မည်
        $drivers = $query->select('name', 'phone', 'balance')->paginate(25);
    
        // Response ပြန်ပေးမည်
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
        $trips = Trip::where('status','completed')->latest()->paginate(25);

        return response()->json($trips);
    }
}
