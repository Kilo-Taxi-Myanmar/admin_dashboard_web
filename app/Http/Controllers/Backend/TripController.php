<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TripController extends Controller
{
    public function index()
    {
        $trips = Trip::latest()->paginate(25);
        $tripsCount = Trip::count();
        $users = User::all();

        return view('backend.trip.index', compact('trips', 'tripsCount','users'));
    }

    public function accepted()
    {
        $trips = Trip::latest()->where('status','accepted')->paginate(25);
        $tripsCount = Trip::count();
        $users = User::all();

        return view('backend.trip.accepted', compact('trips', 'tripsCount','users'));
    }

    public function completed()
    {
        $trips = Trip::latest()->where('status','completed')->paginate(25);
        $tripsCount = Trip::count();
        $users = User::all();

        return view('backend.trip.completed', compact('trips', 'tripsCount','users'));
    }

    public function canceled()
    {
        $trips = Trip::latest()->where('status','canceled')->paginate(25);
        $tripsCount = Trip::count();
        $users = User::all();

        return view('backend.trip.canceled', compact('trips', 'tripsCount','users'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'distance' => 'required|numeric',
            'duration' => 'required|string',
            'waiting_time' => 'required|string',
            'normal_fee' => 'required|numeric',
            'waiting_fee' => 'required|numeric',
            'extra_fee' => 'required|numeric',
            'total_cost' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $trip = Trip::find($id);

        if (!$trip) {
            return redirect()->back()->with('error', 'Trip not found');
        }

        $trip->distance = $request->distance;
        $trip->duration = $request->duration;
        $trip->waiting_time = $request->waiting_time;
        $trip->normal_fee = $request->normal_fee;
        $trip->waiting_fee = $request->waiting_fee;
        $trip->extra_fee = $request->extra_fee;
        $trip->total_cost = $request->total_cost;

        $trip->save();

        return redirect()->back()->with('success', 'Trip updated successfully');
    }

    public function show($id)
    {
        
        $trip = Trip::findOrFail($id);
        $user = User::findOrFail($trip->driver_id);
        $polyline = $trip->polyline; 
        $startLocation = ['lat' => $trip->start_lat, 'lng' => $trip->start_lng]; // Start point coordinates
        $endLocation = ['lat' => $trip->end_lat, 'lng' => $trip->end_lng]; // End point coordinates
        
        if($trip->user_id === null){
        $customer = null;
            
        }else{
            $customer = User::findOrFail($trip->user_id);

        }

        // $transactions = $user->transactions()
        //     ->where('income_outcome', 'income')->latest()
        //     ->paginate(10);
  

        // $tripsQuery = $user->trips();
        // $tripsCount = $tripsQuery->count();
        // $trips = $tripsQuery->latest()->paginate(10);

        return view('backend.trip.show', compact('user', 'customer', 'trip','polyline','startLocation','endLocation'));
    }

    public function destroy($id)
    {
        $trip = Trip::find($id);

        if (!$trip) {
            return response()->json(['error' => 'Trip not found'], 404);
        }

        $trip->delete();

        return redirect()->back();
    }

    public function search(Request $request)
    {
        $keyword = $request->input('key');

        $users = User::where('name', 'LIKE', '%' . $keyword . '%')
            ->orWhere('phone', 'LIKE', '%' . $keyword . '%')
            ->orWhere('driving_license', 'LIKE', '%' . $keyword . '%')
            ->orWhere('nrc_no', 'LIKE', '%' . $keyword . '%')
            ->get();

        $user_ids = collect($users)->pluck('id')->toArray();

        $trips = Trip::whereIn('user_id', $user_ids)->paginate(25);
        $tripsCount = Trip::count();

        return view('backend.trip.index', compact('trips', 'tripsCount'));
    }

    public function searchAccepted(Request $request)
    {
        $keyword = $request->input('key');
        $users = User::all();

        $user = User::where('name', 'LIKE', '%' . $keyword . '%')
            ->orWhere('phone', 'LIKE', '%' . $keyword . '%')
            ->orWhere('driving_license', 'LIKE', '%' . $keyword . '%')
            ->orWhere('nrc_no', 'LIKE', '%' . $keyword . '%')
            ->get();

        $user_ids = collect($user)->pluck('id')->toArray();

        $trips = Trip::whereIn('driver_id', $user_ids)->where('status','accepted')->paginate(25);
        $tripsCount = Trip::count();

        return view('backend.trip.accepted', compact('trips', 'tripsCount','users'));
    }
    public function searchCompleted(Request $request)
    {
        $keyword = $request->input('key');
        $users = User::all();

        $user = User::where('name', 'LIKE', '%' . $keyword . '%')
            ->orWhere('phone', 'LIKE', '%' . $keyword . '%')
            ->orWhere('driving_license', 'LIKE', '%' . $keyword . '%')
            ->orWhere('nrc_no', 'LIKE', '%' . $keyword . '%')
            ->get();

        $user_ids = collect($user)->pluck('id')->toArray();

        $trips = Trip::whereIn('driver_id', $user_ids)->where('status','completed')->paginate(25);
        $tripsCount = Trip::count();

        return view('backend.trip.completed', compact('trips', 'tripsCount','users'));
    }
    public function searchCanceled(Request $request)
    {
        $keyword = $request->input('key');
        $users = User::all();
        $user = User::where('name', 'LIKE', '%' . $keyword . '%')
            ->orWhere('phone', 'LIKE', '%' . $keyword . '%')
            ->orWhere('driving_license', 'LIKE', '%' . $keyword . '%')
            ->orWhere('nrc_no', 'LIKE', '%' . $keyword . '%')
            ->get();

        $user_ids = collect($user)->pluck('id')->toArray();

        $trips = Trip::whereIn('driver_id', $user_ids)->where('status','canceled')->paginate(25);
        $tripsCount = Trip::count();

        return view('backend.trip.canceled', compact('trips', 'tripsCount','users'));
    }
}
