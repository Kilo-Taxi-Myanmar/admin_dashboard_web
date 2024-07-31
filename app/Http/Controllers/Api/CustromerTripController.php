<?php

namespace App\Http\Controllers\Api;

use App\Events\TripEvent;
use App\Events\BookingEvent;
use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\System;
use App\Models\CarType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;



class CustromerTripController extends Controller
{
    public function index(){
        $user = Auth::user();
        $trips = Trip::latest()
        ->where('user_id', $user->id)
        ->join('cartype', 'cartype.id', '=', 'trips.cartype')
        ->orderBy('trips.created_at', 'desc')
        ->select('trips.*', 'cartype.id as cartype_id', 'cartype.type as cartype_type')
        ->get();

    return response()->json($trips);
    }



    public function show($id){
        $trip = Trip::findOrFail($id);
        if($trip->driver_id !== null){
            $driver = User::findOrFail($trip->driver_id);
            
            $vehicle = Vehicle::where('user_id',$trip->driver_id )->first();
            $cartype = CarType::where('id',$trip->cartype)->first();
            $data = [
                "id"=> $trip->id,
                "user_id" => $trip->user_id,
                "distance"=> $trip->distance,
                "duration"=> $trip->duration,
                "waiting_time"=> $trip->waiting_time,
                "normal_fee"=> $trip->normal_fee,
                "waiting_fee"=>  $trip->waiting_fee,
                "extra_fee"=> $trip->extra_fee,
                "initial_fee" =>$trip->initial_fee,
                "total_cost"=> $trip->total_cost,
                "start_lat"=> $trip->start_lat,
                "start_lng"=> $trip->start_lng,
                "end_lat"=> $trip->end_lat,
                "end_lng"=>  $trip->end_lng,
                "status"=> $trip->status,
                "start_address"=>  $trip->start_address,
                "end_address"=>  $trip->end_address,
                "driver"=> $driver,
                "vehicle"=>$vehicle,
                "cartype"=> $cartype,
                "created_at"=>  $trip->created_at,
                "updated_at"=>  $trip->created_at
            ];

                return response()->json($data);

        }
        return response()->json($trip);
       
    }

    public function tripindriverid(Request $request,$id){

        $trip = Trip::findOrFail($id);

        $trip->driver_id =intval( $request->driver_id);
        $trip->save();

        return response()->json($trip);

    }
   
    public function store(Request $request)
    {

        
        $validator = Validator::make($request->all(), [
            'distance' => 'required|numeric',
            'duration' => 'required|string',
            'waiting_time' => 'required|string',
            'normal_fee' => 'required|numeric',
            'waiting_fee' => 'required|numeric',
            'extra_fee' => 'required|numeric',
            'total_cost' => 'required|numeric',
            'start_lat' => 'required|numeric',
            'start_lng' => 'required|numeric',
            'end_lat' => 'required|numeric',
            'end_lng' => 'required|numeric',
            'cartype' => 'required|numeric',
            'start_address'=>'nullable',
            'end_address' => 'nullable'

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        

        $radius = 2; // Initial radius
        $nearestDriver = $this->searchNearbyDrivers($request,$radius);


        if (!$nearestDriver) {
            $radius = 3; // Extend radius to 3 km
            $nearestDriver = $this->searchNearbyDrivers($request,$radius);
        }

        if (!$nearestDriver) {
            $radius = 5; // Extend radius to 5 km
            $nearestDriver = $this->searchNearbyDrivers($request,$radius);
        }
        if ($nearestDriver) {
           
           

            $trip = new Trip();
                 $trip->user_id = Auth::user()->id;
                $trip->distance = $request->distance;
                $trip->duration = $request->duration;
                $trip->waiting_time = $request->waiting_time;
                $trip->normal_fee = $request->normal_fee;
                $trip->waiting_fee = $request->waiting_fee;
                $trip->extra_fee = $request->extra_fee;
                $trip->total_cost = $request->total_cost;
                $trip->start_lat = $request->start_lat;
                $trip->start_lng = $request->start_lng;
                $trip->end_lat = $request->end_lat;
                $trip->end_lng = $request->end_lng;
                $trip->start_address = $request->start_address;
                $trip->end_address = $request->end_address;
                $trip->cartype = $request->cartype;

           
            
            $trip->driver_id = $nearestDriver->id;

            $system = System::findOrFail(1);
            $initial_fee=0;

            if($request->cartype == 1 ){
            $initial_fee=$system->standard_fee;

            }elseif($request->cartype == 2){
            $initial_fee=$system->cargo_fee;

            }else{
            $initial_fee=$system->plus_fee;

            }

            $trip->initial_fee = $initial_fee;
    
            $trip->save();
            $user = User::findOrFail($trip->user_id);
        
            $tripData = [
                "id" => $trip->id,
                "user_id" => $user,
                "distance" => $trip->distance,
                "duration" => $trip->duration,
                "waiting_time" => $trip->waiting_time,
                "normal_fee" => $trip->normal_fee,
                "waiting_fee" => $trip->waiting_fee,
                "extra_fee" => $trip->extra_fee,
                "initial_fee" => $trip->initial_fee,
                "total_cost" => $trip->total_cost,
                "start_lat" => $trip->start_lat,
                "start_lng" => $trip->start_lng,
                "end_lat" => $trip->end_lat,
                "end_lng" => $trip->end_lng,
                "status" => $trip->status,
                "start_address" => $trip->start_address,
                "end_address" => $trip->end_address,
                "driver_id" => $trip->driver_id,
                "cartype" => $trip->cartype,
                "created_at" => $trip->created_at,
                "updated_at" => $trip->updated_at
            ];
            
        $url = 'https://fcm.googleapis.com/fcm/send';
        // $FcmToken = User::whereNotNull('device_token')->pluck('device_token')->all();
        $user = User::where('id', $trip->driver_id)->first();
        $FcmToken = $user->device_token;
        $serverKey = "AAAAFYDzjbw:APA91bHtk8kPufHHYt_EG1HebesEdgWoqEq51Mq0xSE5XBpi_7vBcG7eAAcWUClAiXScaVgb2x56qDCeZ2yb8ln9CwjItVOwt8eYn9Api0ToysWPR5cGhmIymz1Kr3UBrUgjkqV-b3p7";

        $data = [
            "registration_ids" => [$FcmToken],
            "notification" => [
                "title" => "new Order",
                "body" =>"new pending order",
                
            ],
            "data" => $tripData
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
        // dd($FcmToken);
        return response()->json($tripData);

   

          
        } else {
            return response()->json(['message' => 'No nearby drivers found'], 404);
        }

        
    }

    public function update(Request $request, $id)
    {

        // dd($request);
        $validator = Validator::make($request->all(), [
            'distance' => 'required|numeric',
            'duration' => 'required|string',
            'waiting_time' => 'required|string',
            'normal_fee' => 'required|numeric',
            'waiting_fee' => 'required|numeric',
            'extra_fee' => 'required|numeric',
            'total_cost' => 'required|numeric',
            'start_lat' => 'required|numeric',
            'start_lng' => 'required|numeric',
            'end_lat' => 'required|numeric',
            'end_lng' => 'required|numeric',
            'cartype' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $trip = Trip::find($id);

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
        // $trip->start_lat = $request->start_lat;
        // $trip->start_lng = $request->start_lng;
        $trip->end_lat = $request->end_lat;
        $trip->end_lng = $request->start_lng;
        $trip->cartype = $request->cartype;

        $trip->start_address = $request->start_address;
        $trip->end_address = $request->end_address;
        $trip->status = $request->status;
        $trip->driver_id = $request->driver_id;


        $trip->save();
            $driver = User::findOrFail($request->driver_id);
        $trip = [
            'id' => $trip->id,
            'user_id' => Auth::user()->id,
            'distance' => $request->distance,
            'duration' => $request->duration,
            'waiting_time' => $request->waiting_time,
            'normal_fee' => $request->normal_fee,
            'waiting_fee' => $request->waiting_fee,
            'extra_fee' => $request->extra_fee,
            'total_cost' => $request->total_cost,
            'start_lat' => $request->start_lat,
            'start_lng' => $request->start_lng,
            'end_lat' => $request->end_lat,
            'end_lng' => $request->end_lng,
            'status' => $request->status,
            'driver_id' =>$driver,
            'cartype' =>$request->cartype,

             
        ];

   

        return response()->json(['trip'=>$trip,'message' => 'Trip updated successfully'], 200);
    }

    public function destroy($id)
    {
        $trip = Trip::findOrFail($id);

        if (!$trip) {
            return response()->json(['error' => 'Trip not found'], 404);
        }

        $trip->delete();

        return response()->json(['trip' => $trip,'message' => 'Trip deleted successfully'], 200);
    }

    public function activeBooking(){
        $user = Auth::id();

        $trips = Trip::where('user_id',$user)->whereNotIn('status',['completed','canceled'])->get();

        if ($trips->isNotEmpty()) {
            foreach($trips as $trip){
                // dd($trip);
                return response()->json($trip);
            }
           
           
        }

         return response('null');
    }



    private function searchNearbyDrivers($request,$radius){
        
        $latitude = $request->start_lat;
        $longitude = $request->start_lng;
        
                
        $radius = $radius;
        // $cartype = $request->cartype;
        $cartype = intval($request->cartype);


    $nearbyDriver = User::role('user')
            ->where('status','active')
            ->where('available',true)
            ->where('balance','>=',3000)
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
    ->first();

        if ($nearbyDriver) {
            return  $nearbyDriver;
            
        }
    }
}
