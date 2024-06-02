<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\LocationUpdated;
use App\Models\User;

class LocationUpdatedController extends Controller
{
    public function updateLocation(Request $request)
    {
        // Validate request and get user ID, latitude, and longitude from request
        $userId = $request->user()->id;
        $lat = $request->input('lat');
        $lng = $request->input('lng');

        // Update user's location in the database
        // ...
    

        // Dispatch LocationUpdated event
        event(new LocationUpdated($userId, $lat, $lng));

        // Return response
        return response()->json(['message' => 'Location updated successfully'], 200);
    }
}
