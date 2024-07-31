<?php

namespace App\Http\Controllers\Api;

use App\Events\BookingEvent;
use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Pusher\Pusher;

class DriverSearchController extends Controller
{
    public function searcTripnearhDriver(){
        $trip = Trip::find(302);
        

       

        $radius = 2; // Initial radius
        $nearestDriver = $this->searchNearbyDrivers($trip->cartype,$radius);


        if (!$nearestDriver) {
            $radius = 3; // Extend radius to 3 km
            $nearestDriver = $this->searchNearbyDrivers($trip->cartype,$radius);
        }

        if ($nearestDriver) {
            // $trip->driver_id = $nearestDriver->id;
            $trip->save();
            // broadcast(new BookingEvent($trip));
            return response()->json($nearestDriver);
        } else {
            return response()->json(['message' => 'No nearby drivers found'], 404);
        }

           
            // return response()->json($trip);

    
    }


    private function searchNearbyDrivers($triptype,$radius){
        $customerLatitude = 27.533;
        $customerLongitude = 22.733;
                
        $radius = $radius;
       

                // $drivers = User::where('available', true)->whereBetween('lat', [$lower_latitude, $upper_latitude])->whereBetween('lng', [$lower_longitude, $upper_longitude])->get();
                // $nearbyDrivers = User::where('available', true)
                // ->where('status','active')       
                // ->selectRaw(
                //     'id, ( 6371 * acos( cos( radians(?) ) *
                //       cos( radians( lat ) )
                //       * cos( radians( lng ) - radians(?)
                //       ) + sin( radians(?) ) *
                //       sin( radians( lat ) ) )
                //     ) AS distance', [$latitude, $longitude, $latitude])
                // ->having('distance', '<=', $radius)
                // ->orderBy('distance')
                // ->first();
                
                // $vehicles = Vehicle::whereRaw("JSON_CONTAINS(type, '1')")->get();
                // $nearbyDriver = User::role('user')
                // ->whereHas('vehicle', function ($query) use ($triptype) {
                //     $query->whereJsonContains('type',$triptype);
                // })
                // ->with('vehicle')
                // ->selectRaw('id,lat, lng, SQRT(
                //     POW(69.1 * (lat - ?), 2) +
                //     POW(69.1 * (? - lng) * COS(? / 57.3), 2)) AS distance', [$customerLatitude, $customerLongitude, $customerLatitude])
                // ->having('distance', '<', $radius)
                // ->orderBy('distance')
                // ->where('available', true)
                // ->where('status', 'active')
                // ->get();
            
            //     $nearbyDrivers = DB::table('users')
            // ->select('users.id',
            //          DB::raw("6371 * acos(cos(radians($customerLatitude)) *
            //                   cos(radians(users.lat)) *
            //                   cos(radians(users.lng) - radians($customerLongitude)) +
            //                   sin(radians($customerLatitude)) *
            //                   sin(radians(users.lat))) AS distance")
            // ) 
            //  ->whereHas('vehicle', function ($query) use ($triptype) {
            //         $query->whereJsonContains('type',1);
            //     })->with('vehicle')
            //  ->orderBy('distance')
            // ->get();


        $nearbyDrivers = User::role('user')
                ->where('status','active')
                ->where('available',true)
                ->select('users.id',
                DB::raw("6371 * acos(cos(radians($customerLatitude)) *
                        cos(radians(users.lat)) *
                        cos(radians(users.lng) - radians($customerLongitude)) +
                        sin(radians($customerLatitude)) *
                        sin(radians(users.lat))) AS distance")
        )
        ->having('distance', '<', $radius)
        ->whereHas('vehicle', function ($query) use ($triptype) {
            $query->whereJsonContains('type', 3);
        })
        ->with('vehicle')

        ->orderBy('distance')
        ->first();
        
        $nearbyDriver = $nearbyDrivers;
            
            
            if ($nearbyDriver) {
                return  $nearbyDriver;
                
            }


    }

    public function drivercancleanotherdriver(Request $request){
     
        $driverid = $request->driver_id;
                
        
        $radius = 2; // Initial radius
        $nearestDriver = $this->drivercanclseardriver($driverid,$radius);


        if (!$nearestDriver) {
            $radius = 3; // Extend radius to 3 km
            $nearestDriver = $this->drivercanclseardriver($driverid,$radius);
        }

        if (!$nearestDriver) {
            $radius = 5; // Extend radius to 5 km
            $nearestDriver = $this->drivercanclseardriver($driverid,$radius);
        }


        if ($nearestDriver) {
            
            
            return response()->json($nearestDriver);
        } else {
            return response()->json(['message' => 'No nearby drivers found'], 404);
        }
    
    }

    private function drivercanclseardriver($driverid,$radius){
        $latitude = 27.533;
        $longitude =22.732;
        $driverid = $driverid;

                $nearbyDrivers = User::role('user')
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
        ->whereHas('vehicle', function ($query) {
            $query->whereJsonContains('type', 3);
        })
        ->with('vehicle')

        ->orderBy('distance')
        ->first();

        $nearbyDriver = $nearbyDrivers;
            $nearbyDriver = [
                $nearbyDriver,
                $driverid
            ];
            

            if ($nearbyDriver) {
                return  $nearbyDriver;
                
            }
    }

    //driver id search trip
    public function searchTrip($id){

       
        
       

        $trip = Trip::where('driver_id', $id)
            ->where('status', '!=', 'canceled')
            ->where('status','!=', 'completed')
            ->first();

        // dd($trip);
                    $tripData ='';
                    if($trip !== null){
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

                             return response()->json($tripData);
                    }
                    else{
                        return response()->json($tripData);
                    }
                    
  
        
        
        


       
    }

    //trip id search trip

    public function searchTripId($id){

        $trip = Trip::findOrFail($id);


        $tripData ='';
        if($trip !== null){
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

                 return response()->json($tripData);
        }
        else{
            return response()->json($tripData);
        }

    }
}

