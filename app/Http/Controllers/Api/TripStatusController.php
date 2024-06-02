<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class TripStatusController extends Controller
{
     public function start(Request $request,$id){
        

        $validator = Validator::make($request->all(), [
            'distance' => 'required|numeric',
            'duration' => 'required|string',
            'waiting_time' => 'required|string',
            'normal_fee' => 'required|numeric',
            'waiting_fee' => 'required|numeric',
            'extra_fee' => 'required|numeric',
            'total_cost' => 'required|numeric',
            'end_lat' => 'required|numeric',
            'end_lng' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $trip = Trip::findOrFail($id);

        if (!$trip) {
            return response()->json(['error' => 'Trip not found'], 404);
        }

        $trip->distance = $request->distance;
        $trip->duration = $request->duration;
        $trip->waiting_time = $request->waiting_time;
        $trip->normal_fee = $request->normal_fee;
        $trip->waiting_fee = $request->waiting_fee;
        $trip->extra_fee = $request->extra_fee;
        $trip->total_cost = $request->total_cost;
        $trip->end_lat = $request->end_lat;
        $trip->end_lng = $request->start_lng;
        $trip->created_at = now();

        $trip->save();

        $driver = User::findOrFail($trip->driver_id);


        if($driver->available === true){
            $driver->available = false;
            $driver->save();
        }

        return response()->json([$trip,$driver]);

        
     }
     
     public function end(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'distance' => 'required|numeric',
            'duration' => 'required|string',
            'waiting_time' => 'required|string',
            'normal_fee' => 'required|numeric',
            'waiting_fee' => 'required|numeric',
            'extra_fee' => 'required|numeric',
            'total_cost' => 'required|numeric',
            'end_lat' => 'required|numeric',
            'end_lng' => 'required|numeric',
            'status' =>'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $trip = Trip::findOrFail($id);

        if (!$trip) {
            return response()->json(['error' => 'Trip not found'], 404);
        }

        $trip->distance = $request->distance;
        $trip->duration = $request->duration;
        $trip->waiting_time = $request->waiting_time;
        $trip->normal_fee = $request->normal_fee;
        $trip->waiting_fee = $request->waiting_fee;
        $trip->extra_fee = $request->extra_fee;
        $trip->total_cost = $request->total_cost;
        $trip->end_lat = $request->end_lat;
        $trip->end_lng = $request->start_lng;
        $trip->status = $request->status;
        $trip->start_address = $request->start_address;
        $trip->end_address = $request->end_address;

        $trip->save();
        $driver = User::findOrFail($trip->driver_id);


        if($driver->available === false){
            $driver->available = true;
            $driver->save();
        }

        return response()->json([$trip]);

     }
     public function waiting (Request $request,$id){
        $validator = Validator::make($request->all(), [
            'distance' => 'required|numeric',
            'duration' => 'required|string',
            'waiting_time' => 'required|string',
            'normal_fee' => 'required|numeric',
            'waiting_fee' => 'required|numeric',
            'extra_fee' => 'required|numeric',
            'total_cost' => 'required|numeric',
            'end_lat' => 'required|numeric',
            'end_lng' => 'required|numeric',
            'status' =>'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $trip = Trip::findOrFail($id);

        if (!$trip) {
            return response()->json(['error' => 'Trip not found'], 404);
        }

        $trip->distance = $request->distance;
        $trip->duration = $request->duration;
        $trip->waiting_time = $request->waiting_time;
        $trip->normal_fee = $request->normal_fee;
        $trip->waiting_fee = $request->waiting_fee;
        $trip->extra_fee = $request->extra_fee;
        $trip->total_cost = $request->total_cost;
        $trip->end_lat = $request->end_lat;
        $trip->end_lng = $request->start_lng;
        $trip->status = $request->status;
        $trip->start_address = $request->start_address;
        $trip->end_address = $request->end_address;

        $trip->save();

        return response()->json($trip);

     }

     public function cash($id){
        $trip = Trip::findOrFail($id);
        if($trip->driver_id !== null){
            $driver = User::findOrFail($trip->driver_id);
            $trip->driver_id = $driver;
            
            $data = [
                "id"=> $trip->id,
                "user_id" => $trip->user_id,
                "distance"=> "34.63",
                "duration"=> "3.45",
                "waiting_time"=> "0",
                "normal_fee"=> "3000",
                "waiting_fee"=>  "0",
                "extra_fee"=> "3888",
                "total_cost"=> "4334",
                "start_lat"=> 78.2172,
                "start_lng"=> 61.4063,
                "end_lat"=> "83.3445",
                "end_lng"=>  null,
                "status"=> "pending",
                "start_address"=>  null,
                "end_address"=>  null,
                "driver"=> $driver,
                "cartype"=> "2",
                "created_at"=>  "2024-02-03T05:34:28.000000Z",
                "updated_at"=>  "2024-02-11T12:58:09.000000Z"
            ];

                return response()->json($data);

        }
        return response()->json($trip);
     }

     public function extraFee(){
        $fees = Fee::all();
        return response()->json($fees);

     }
}
