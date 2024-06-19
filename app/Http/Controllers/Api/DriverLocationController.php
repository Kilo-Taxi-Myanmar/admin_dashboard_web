<?php

namespace App\Http\Controllers\Api;

use App\Events\DriverLocationUpdate;
use App\Http\Controllers\Controller;
use App\Models\CarType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DriverLocationController extends Controller
{

    public function neardriverall(Request $request){
        $latitude = $request->start_lat;
        $longitude = $request->start_lng;


        $radius = 5;

        $cartype = intval($request->cartype);



        $nearbyDriver = User::role('user')
        ->where('status','active')
        ->where('available',true)
        ->select('users.id',
         DB::raw("6371 * acos(cos(radians($latitude)) *
                  cos(radians(users.lat)) *
                  cos(radians(users.lng) - radians($longitude)) +
                  sin(radians($latitude)) *
                  sin(radians(users.lat))) AS distance")
                )
                ->having('distance', '<', $radius)
                ->whereHas('vehicle', function ($query) use ($cartype) {
                    $query->whereJsonContains('type', $cartype);
                })
                ->with('vehicle')

                ->orderBy('distance')
                ->get();

            if($nearbyDriver){

//                broadcast(new TripNearDriverAllEvent($nearbyDriver))->toOthers();

                return response()->json($nearbyDriver, 200);
            }else{
                return response()->json(["message"=>"driver not found"]);
            }





    }

    public function show($id){
        $driver = User::findOrFail($id);
        return response()->json($driver);

    }

    public function driverall(){
        $driver = User::role('user')->get();


        return response()->json($driver);


    }

    public function driverallcustomer(){
        $driver = User::role('user')->where('available',true)->get();


        return response()->json($driver);


    }

    public function cartype(){
        $cartypes = CarType::all();

        return response()->json($cartypes);

    }



    public function driverupdate(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = User::findOrFail($id);

        $user->lat = $request->lat;
        $user->lng = $request->lng;

        $user->save();

        $data = [
            'id'=> $user->id,
            'lat'=>$request->lat,
            'lng' =>$request->lng
        ];

        $driver= User::role('user')->get();
//        broadcast(new DriverLocationUpdate($user));
//

        return response()->json(['driver'=>$user ,'message' => 'Driver updated successfully'], 200);
    }


    public function driverAvailableUpdate(Request $request){
        $user = Auth::user();

        $user->available = $request->available;

        $user->save();

        if($user){
            return response()->json($user,200);
        }

        return response()->json(['message'=>'User Not Fount','status'=>404]);

    }
}
