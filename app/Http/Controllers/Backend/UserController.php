<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CarType;
use App\Models\Transaction;
use App\Models\Trip;
use App\Models\User;
use App\Models\UserImage;
use App\Models\Vehicle;
use Carbon\Carbon;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use App\Services\SMSService;
class UserController extends Controller
{
    public function index()
    {
        // Use the query builder to construct the SQL query
        $usersQuery = User::role('user');

        // Get the total count of all users
        $usersCount = $usersQuery->count();

        // Paginate the results
        $users = $usersQuery->paginate(25);

        return view('backend.users.index', compact('users', 'usersCount'));
    }






    public function activeUser()
    {
        // Use the query builder to construct the SQL query
        $usersQuery = User::where('status', 'active')->role('user');

        // Get the total count of active users
        $usersCount = $usersQuery->count();

        // Paginate the results
        $users = $usersQuery->paginate(25);

        return view('backend.users.activeUser', compact('users', 'usersCount'));
    }


    public function pendingUser()
    {
        // Use the query builder to construct the SQL query
        $usersQuery = User::where('status', 'pending')->role('user');

        // Get the total count of pending users
        $usersCount = $usersQuery->count();

        // Paginate the results
        $users = $usersQuery->paginate(25);

        return view('backend.users.pendingUser', compact('users', 'usersCount'));
    }

    public function turnActive($user_id)
    {
        $user = User::find($user_id);
        $user->status = 'active';
        $user->update();
        return redirect()->route('users.pending');
    }

    public function turnPending($user_id)
    {
        $user = User::find($user_id);
        $user->status = 'pending';
        $user->update();
        return redirect()->route('users.active');
    }


    public function create()
    {
        $cartypes = CarType::all();
        return view('backend.users.create',compact('cartypes'));
    }

    public function store(Request $request)
    {




        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone|max:255',
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'email' => 'nullable|email|unique:users,email|max:255',
            'birth_date' => 'nullable',
            'address' => 'nullable|string|max:255',
            'nrc_no' => 'nullable|string|unique:users,nrc_no|max:255',
            'driving_license' => 'nullable|string|unique:users,driving_license|max:255',
            'profile_image' => 'required|image',
            'vehicle_plate_no' => 'required|string|max:255',
            'vehicle_model' => 'required|string|max:255',
            'type' =>'required'
        ]);

        try {
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'birth_date' => $validatedData['birth_date'],
                'address' => $validatedData['address'],
                'nrc_no' => $validatedData['nrc_no'],
                'driving_license' => $validatedData['driving_license'],
                'password' => Hash::make($validatedData['password']),
            ])->assignRole('user');
            $user->driver_id = sprintf('%04d', $user->id - 1);

        } catch (ValidationException $e) {
            return back()->withErrors($e->getMessage());
        }

        // $vehicleData = $request->validate([
        //     'vehicle_plate_no' => 'required|string|max:255',
        //     'vehicle_model' => 'required|string|max:255',
        //     'type' =>'required'
        // ]);

        $cartypes = array_map('intval', $request->type);

        $vehicle = new Vehicle();
        $vehicle->user_id = $user->id;
        $vehicle->type = json_encode($cartypes);

        if ($request->has('vehicle_plate_no')) {
            $vehicle->vehicle_plate_no = $request->vehicle_plate_no;
        }
        if ($request->has('vehicle_model')) {
            $vehicle->vehicle_model = $request->vehicle_model;
        }
        if ($request->hasFile('vehicle_image')) {
            $vehicleImage = $request->file('vehicle_image');
            $vehicleImageName = time() . '.' . $vehicleImage->getClientOriginalExtension();
            $vehicleImage->storeAs('uploads/images/vehicles', $vehicleImageName);
            $vehicle->vehicle_image_url = $vehicleImageName;
        }


        $validateImage = $request->validate([
            'profile_image' => 'required|image',
            'front_nrc_image' => 'nullable|image',
            'back_nrc_image' => 'nullable|image',
            'front_license_image' => 'nullable|image',
            'back_license_image' => 'nullable|image',
            'vehicle_image' => 'nullable|image'
        ]);
        $userImage = new UserImage();
        $userImage->user_id = $user->id;

        // // upload and save profile image
        if ($request->hasFile('profile_image')) {
            $profileImage = $request->file('profile_image');
            $profileImageName = time() . '.' . $profileImage->getClientOriginalExtension();
            $profileImage->storeAs('uploads/images/profiles', $profileImageName);
            $userImage->profile_image = $profileImageName;
        }

        // upload and save front NRC image
        if ($request->hasFile('front_nrc')) {
            $frontNrcImage = $request->file('front_nrc');
            $frontNrcImageName = time() . '.' . $frontNrcImage->getClientOriginalExtension();
            $frontNrcImage->storeAs('uploads/images/front_nrcs', $frontNrcImageName);
            $userImage->front_nrc_image = $frontNrcImageName;
        }

        // // upload and save back NRC image
        // if ($request->hasFile('back_nrc')) {
        //     $backNrcImage = $request->file('back_nrc');
        //     $backNrcImageName = time() . '.' . $backNrcImage->getClientOriginalExtension();
        //     $backNrcImage->storeAs('uploads/images/back_nrcs', $backNrcImageName);
        //     $userImage->back_nrc_image = $backNrcImageName;
        // }

        // // upload and save front license image
        // if ($request->hasFile('front_license')) {
        //     $frontLicenseImage = $request->file('front_license');
        //     $frontLicenseImageName = time() . '.' . $frontLicenseImage->getClientOriginalExtension();
        //     $frontLicenseImage->storeAs('uploads/images/front_licenses', $frontLicenseImageName);
        //     $userImage->front_license_image = $frontLicenseImageName;
        // }

        // // upload and save back license image
        // if ($request->hasFile('back_license')) {
        //     $backLicenseImage = $request->file('back_license');
        //     $backLicenseImageName = time() . '.' . $backLicenseImage->getClientOriginalExtension();
        //     $backLicenseImage->storeAs('uploads/images/back_licenses', $backLicenseImageName);
        //     $userImage->back_license_image = $backLicenseImageName;
        // }

        // save user images to database

        $vehicle->save();
        $userImage->save();
        $user->save();
        // return redirect()->route('user.index', ['user' => $user]);
        return redirect(route('users.index'));
    }

    public function show(User $user)
    {
        $transactions = $user->transactions()
            ->where('income_outcome', 'income')->latest()
            ->paginate(10);

        $tripsQuery = $user->trips();
        $tripsCount = $tripsQuery->count();
        $trips = $tripsQuery->latest()->paginate(10);

         $driver = $user; // Assuming User model has latitude and longitude fields
        $latitude = $driver->latitude;
        $longitude = $driver->longitude;

        return view('backend.users.show', compact('user', 'transactions', 'trips', 'tripsCount', 'latitude', 'longitude'));

    }


    public function edit(User $user)
    {
        $cartypes = CarType::all();
        return view('backend.users.edit', compact('user','cartypes'));
    }

    public function update(Request $request, User $user)
    {


        $validatedData = $request->validate([
            'driver_id' => 'required',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'password' => ['nullable', 'string', 'min:8', 'max:255'],
            'email' => 'nullable|email|max:255',
            'birth_date' => 'nullable',
            'address' => 'nullable|string|max:255',
            'nrc_no' => 'nullable|string|max:255',
            'driving_license' => 'nullable|string|max:255',
            'vehicle_plate_no' => 'required|string|max:255',
            'vehicle_model' => 'required|string|max:255',
            'type' =>'required',
            'profile_image' => 'nullable|image',


        ]);
        // dd($request);

        try {
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->phone = $validatedData['phone'];
            $user->birth_date = $validatedData['birth_date'];
            $user->address = $validatedData['address'];
            $user->nrc_no = $validatedData['nrc_no'];
            $user->driving_license = $validatedData['driving_license'];
            $user->driver_id = $validatedData['driver_id'];

            if ($validatedData['password']) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            }
            $user->save();
        } catch (ValidationException $e) {
            return back()->withErrors($e->getMessage());
        }

        $validateImage = $request->validate([
            'profile_image' => 'nullable|image',
            // 'front_nrc_image' => 'nullable|image',
            // 'back_nrc_image' => 'nullable|image',
            // 'front_license_image' => 'nullable|image',
            // 'back_license_image' => 'nullable|image',
            // 'vehicle_image' => 'nullable|image'
        ]);

        if ($user->userImage) {
            $userImage = UserImage::where('user_id', $user->id)->get()->first();
        } else {
            $userImage = new UserImage();
            $userImage->user_id = $user->id;
        }
        // update profile image
        if ($request->hasFile('profile_image')) {

            if (!is_null($userImage->profile_image) && Storage::exists('uploads/images/profiles/' . $userImage->profile_image)) {
                Storage::delete('uploads/images/profiles/' . $userImage->profile_image); //delete old image
            }
            $profileImage = $request->file('profile_image');
            $profileImageName = time() . '.' . $profileImage->getClientOriginalExtension();
            $profileImage->storeAs('uploads/images/profiles', $profileImageName);
            $userImage->profile_image = $profileImageName;
        }

        // // update front NRC image
        // if ($request->hasFile('front_nrc')) {
        //     if (!is_null($userImage->front_nrc_image) && Storage::exists('uploads/images/front_nrcs/' . $userImage->front_nrc_image)) {
        //         Storage::delete('uploads/images/front_nrcs/' . $userImage->front_nrc_image); //delete old image
        //     }
        //     $frontNrcImage = $request->file('front_nrc');
        //     $frontNrcImageName = time() . '.' . $frontNrcImage->getClientOriginalExtension();
        //     $frontNrcImage->storeAs('uploads/images/front_nrcs/', $frontNrcImageName);
        //     $userImage->front_nrc_image = $frontNrcImageName;
        // }

        // // update back NRC image
        // if ($request->hasFile('back_nrc')) {
        //     if (!is_null($userImage->back_nrc_image) && Storage::exists('uploads/images/back_nrcs/' . $userImage->back_nrc_image)) {
        //         Storage::delete('uploads/images/back_nrcs/' . $userImage->back_nrc_image); //delete old image
        //     }
        //     $backNrcImage = $request->file('back_nrc');
        //     $backNrcImageName = time() . '.' . $backNrcImage->getClientOriginalExtension();
        //     $backNrcImage->storeAs('uploads/images/back_nrcs/', $backNrcImageName);
        //     $userImage->back_nrc_image = $backNrcImageName;
        // }

        // // update front license image
        // if ($request->hasFile('front_license')) {
        //     if (!is_null($userImage->front_license_image) && Storage::exists('uploads/images/front_licenses/' . $userImage->front_license_image)) {
        //         Storage::delete('uploads/images/front_licenses/' . $userImage->front_license_image); //delete old image
        //     }
        //     $backNrcImage = $request->file('front_license');
        //     $backNrcImageName = time() . '.' . $backNrcImage->getClientOriginalExtension();
        //     $backNrcImage->storeAs('uploads/images/front_licenses/', $backNrcImageName);
        //     $userImage->front_license_image = $backNrcImageName;
        // }

        // // update back license image
        // if ($request->hasFile('back_license')) {
        //     if (!is_null($userImage->back_license_image) && Storage::exists('uploads/images/back_licenses/' . $userImage->back_license_image)) {
        //         Storage::delete('uploads/images/back_licenses/' . $userImage->back_license_image); //delete old image
        //     }
        //     $backLicenseImage = $request->file('back_license');
        //     $backLicenseImageName = time() . '.' . $backLicenseImage->getClientOriginalExtension();
        //     $backLicenseImage->storeAs('uploads/images/back_licenses/', $backLicenseImageName);
        //     $userImage->back_license_image = $backLicenseImageName;
        // }
        $userImage->save();
        // Vehicle Data update
        $vehicleData = $request->validate([
            'vehicle_plate_no' => 'required|string|max:255',
            'vehicle_model' => 'required|string|max:255',
            'type' => 'required'
        ]);
        if ($user->has('vehicle')) {
            $vehicle = Vehicle::where('user_id', $user->id)->get()->first();
        } else {
            $vehicle = new Vehicle();
            $vehicle->user_id = $user->id;
        }

        $vehicle->vehicle_plate_no = $vehicleData['vehicle_plate_no'];
        $vehicle->vehicle_model = $vehicleData['vehicle_model'];
        $vehicle->type = json_encode($vehicleData['type']) ;
        // if ($request->hasFile('vehicle_image')) {

        //     $oldImage = $vehicle->vehicle_image_url; //get old image by ID
        //     Storage::delete('uploads/images/vehicles/' . $oldImage); //delete old image

        //     $vehicleImage = $request->file('vehicle_image');
        //     $vehicleImageName = time() . '.' . $vehicleImage->getClientOriginalExtension();
        //     $vehicleImage->storeAs('uploads/images/vehicles', $vehicleImageName);
        //     $vehicle->vehicle_image_url = $vehicleImageName;
        // }

        $vehicle->save();
        return redirect()->route('users.index', ['user' => $user]);
    }

    public function destroy(User $user)
    {
        if ($user->has('userImage')) {
            $userImage = $user->userImage;
            if (isset($userImage->profile_image)) {
                Storage::delete('uploads/images/profiles/' . $userImage->profile_image);
            }
            if (isset($userImage->front_nrc_image)) {
                Storage::delete('uploads/images/front_nrcs/' . $userImage->front_nrc_image);
            }
            if (isset($userImage->back_nrc_image)) {
                Storage::delete('uploads/images/back_nrcs/' . $userImage->back_nrc_image);
            }
            if (isset($userImage->front_license_image)) {
                Storage::delete('uploads/images/front_licenses/' . $userImage->front_license_image);
            }
            if (isset($userImage->back_license_image)) {
                Storage::delete('uploads/images/back_licenses/' . $userImage->back_license_image);
            }
        }

        if (isset($user->vehicle)) {
            $vehicle = Vehicle::find($user->vehicle->id);
            Storage::delete('uploads/images/vehicles/' . $vehicle->vehicle_image_url);
            $vehicle->delete();
        }
        $user->tokens()->delete();
        $user->delete();
        return redirect()->route('users.index');
    }

    public function search(Request $request)
    {
        $key = $request->input('key');

        // Use the query builder to construct the SQL query
        $usersQuery = User::role('user')
            ->where(function ($query) use ($key) {
                $query->where('name', 'LIKE', "%$key%")
                    ->orWhere('driver_id', '=', $key)
                    ->orWhere('email', 'LIKE', "%$key%")
                    ->orWhere('phone', 'LIKE', "%$key%");
            });

        // Get the count of all users
        $usersCount = $usersQuery->count();

        // Paginate the results
        $users = $usersQuery->paginate(25);

        return view('backend.users.index', compact('users', 'usersCount'));
    }

    public function searchForActive(Request $request)
    {
        $key = $request->input('key');

        // Use the query builder to construct the SQL query
        $usersQuery = User::role('user')
            ->where('status', 'active')
            ->where(function ($query) use ($key) {
                $query->where('name', 'LIKE', "%$key%")
                    ->orWhere('driver_id', '=', $key)
                    ->orWhere('email', 'LIKE', "%$key%")
                    ->orWhere('phone', 'LIKE', "%$key%");
            });

        // Get all users that match the search criteria
        $usersCount = $usersQuery->count();

        // Paginate the results
        $users = $usersQuery->paginate(25);

        return view('backend.users.activeUser', compact('users', 'usersCount'));
    }

    public function searchForPending(Request $request)
    {
        $key = $request->input('key');

        // Use the query builder to construct the SQL query
        $usersQuery = User::role('user')
            ->where('status', 'pending')
            ->where(function ($query) use ($key) {
                $query->where('name', 'LIKE', "%$key%")
                    ->orWhere('driver_id', '=', $key)
                    ->orWhere('email', 'LIKE', "%$key%")
                    ->orWhere('phone', 'LIKE', "%$key%");
            });

        // Get all users that match the search criteria
        $usersCount = $usersQuery->count();

        // Paginate the results
        $users = $usersQuery->paginate(25);

        return view('backend.users.activeUser', compact('users', 'usersCount'));
    }

    public function searchForRole(Request $request)
    {
        $key = $request->input('key');

        // Use the query builder to construct the SQL query
        $usersQuery = User::role('user')
            ->where(function ($query) use ($key) {
                $query->where('name', 'LIKE', "%$key%")
                    ->orWhere('driver_id', '=', $key)
                    ->orWhere('email', 'LIKE', "%$key%")
                    ->orWhere('phone', 'LIKE', "%$key%");
            });
        $users = $usersQuery->paginate(25);

        // Get the total count of users
        $usersCount = $usersQuery->count();
        $roles = Role::where('name', '!=', 'admin')->get();

        return view('backend.users.user-Management', ['users' => $users, 'roles' => $roles, 'usersCount' => $usersCount]);
    }


    public function userManagement()
    {
        // Use the query builder to construct the SQL query
        $usersQuery = User::role(['user', 'staff']);

        // Get the total count of all users
        $usersCount = $usersQuery->count();

        // Paginate the results
        $users = $usersQuery->paginate(25);

        // Use pluck() method to get only the 'id' field of the roles
        $roles = Role::where('name', '!=', 'admin')->get();

        return view('backend.users.user-Management', ['users' => $users, 'roles' => $roles, 'usersCount' => $usersCount]);
    }



    public function changeRole(Request $request, User $user)
    {
        $validate = $request->validate([
            'role' => 'required|string'
        ]);
        $user->syncRoles([$request->input('role')]);
        return redirect()->back()->with('success', 'Role changed Successfully');
    }

    public function adminProfile()
    {
        return view('backend.users.adminProfile', ['user' => Auth::user()]);
    }
    public function adminUpdate(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:8|max:255',
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();
        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    public function usersSummaryPage(Request $request)
    {
        $sortBy = $request->input('type');

        $usersQuery = User::role('user');

        if ($sortBy == 'most-balance') {
            $usersQuery = $usersQuery->orderBy('balance', 'desc');
        } elseif ($sortBy == 'least-balance') {
            $usersQuery = $usersQuery->orderBy('balance', 'asc');
        } elseif ($sortBy == 'maximum-travel' || $sortBy == 'minimum-travel') {
            $date = $request->input('date');

            if ($date == 'today') {
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
            } elseif ($date == 'start-10') {
                $startDate = now()->startOfMonth();
                $endDate = now()->startOfMonth()->addDays(10);
            } elseif ($date == '10-20') {
                $startDate = now()->startOfMonth()->addDays(10);
                $endDate = now()->startOfMonth()->addDays(20);
            } elseif ($date == '20-end') {
                $startDate = now()->startOfMonth()->addDays(20);
                $endDate = now()->endOfMonth();
            } elseif ($date == 'this-month') {
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
            } elseif ($date == 'this-year') {
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
            }
            $usersQuery = $usersQuery->withCount(['trips as total_trips' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
                ->having('total_trips', '>', 0)
                ->orderBy('total_trips', ($sortBy == 'maximum-travel') ? 'desc' : 'asc');
        }

        $usersCount = $usersQuery->count();
        $users = $usersQuery->paginate(25);
        if ($sortBy == 'maximum-travel' || $sortBy == 'minimum-travel') {
            $users->each(function ($user) use ($startDate, $endDate) {
                return $user->setTotalTrips($startDate, $endDate);
            });
        } else {
            $users->each(function ($user) {
                return $user->setTotalTrips(null, null);
            });
        }
        return view('backend.users.userSummary', compact('users', 'usersCount'));
    }

    public function searchForUsersSummary(Request $request)
    {
        $key = $request->input('key');
        $sortBy = $request->input('type');
        // Use the query builder to construct the SQL query
        $usersQuery = User::role('user')
        ->where(function ($query) use ($key) {
            $query->where('name', 'LIKE', "%$key%")
            ->orWhere('driver_id', '=', $key)
                ->orWhere('email', 'LIKE', "%$key%")
                ->orWhere('phone', 'LIKE', "%$key%");
        });
        if ($sortBy == 'most-balance') {
            $usersQuery = $usersQuery->orderBy('balance', 'desc');
        } elseif ($sortBy == 'least-balance') {
            $usersQuery = $usersQuery->orderBy('balance', 'asc');
        } elseif ($sortBy == 'maximum-travel' || $sortBy == 'minimum-travel') {
            $date = $request->input('date');

            if ($date == 'today') {
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
            } elseif ($date == 'start-10') {
                $startDate = now()->startOfMonth();
                $endDate = now()->startOfMonth()->addDays(10);
            } elseif ($date == '10-20') {
                $startDate = now()->startOfMonth()->addDays(10);
                $endDate = now()->startOfMonth()->addDays(20);
            } elseif ($date == '20-end') {
                $startDate = now()->startOfMonth()->addDays(20);
                $endDate = now()->endOfMonth();
            } elseif ($date == 'this-month') {
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
            } elseif ($date == 'this-year') {
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
            }
            $usersQuery = $usersQuery->withCount(['trips as total_trips' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
                ->having('total_trips', '>', 0)
                ->orderBy('total_trips', ($sortBy == 'maximum-travel') ? 'desc' : 'asc');
        }

        $usersCount = $usersQuery->count();
        $users = $usersQuery->paginate(25);
        if ($sortBy == 'maximum-travel' || $sortBy == 'minimum-travel') {
            $users->each(function ($user) use ($startDate, $endDate) {
                return $user->setTotalTrips($startDate, $endDate);
            });
        } else {
            $users->each(function ($user) {
                return $user->setTotalTrips(null, null);
            });
        }
        return view('backend.users.userSummary', compact('users', 'usersCount'));
    }

    public function userChart($id, $range)
    {
        $user = User::find($id);
        if ($range === 'day') {
            $startDate = Carbon::now()->startOfDay();
            $endDate = Carbon::now()->endOfDay();

            $transactions = $user->transactions()->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at')
                ->get();

            $collection = collect($transactions);

            $transactions = $collection->groupBy(function ($transaction) {
                return Carbon::parse($transaction->created_at)->format('h');
            })->map(function ($group) {
                $topup = $group->where('income_outcome', 'income')->sum('amount');
                $commission = $group->where('income_outcome', 'outcome')->sum('amount');

                return [
                    'date' => $group->first()->created_at->format('h:m'),
                    'topup' => $topup,
                    'commission' => $commission
                ];
            });
        } elseif ($range === 'week') {
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();

            $transactions = $user->transactions()->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at')
                ->get();

            $collection = collect($transactions);

            $transactions = $collection->groupBy(function ($transaction) {
                return Carbon::parse($transaction->created_at)->format('Y-m-d');
            })->map(function ($group) {
                $topup = $group->where('income_outcome', 'income')->sum('amount');
                $commission = $group->where('income_outcome', 'outcome')->sum('amount');

                return [
                    'date' => $group->first()->created_at->format('F j'),
                    'topup' => $topup,
                    'commission' => $commission
                ];
            });
        } elseif ($range === 'month') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();

            $transactions = $user->transactions()->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at')
                ->get();

            $collection = collect($transactions);

            $transactions = $collection->groupBy(function ($transaction) {
                return Carbon::parse($transaction->created_at)->format('Y-m-d');
            })->map(function ($group) {
                $topup = $group->where('income_outcome', 'income')->sum('amount');
                $commission = $group->where('income_outcome', 'outcome')->sum('amount');

                return [
                    'date' => $group->first()->created_at->format('F j'),
                    'topup' => $topup,
                    'commission' => $commission
                ];
            });
        } elseif ($range === 'year') {
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfYear();

            $transactions = $user->transactions()->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at')
                ->get();

            $collection = collect($transactions);

            $transactions = $collection->groupBy(function ($transaction) {
                return Carbon::parse($transaction->created_at)->format('Y');
            })->map(function ($group) {
                $topup = $group->where('income_outcome', 'income')->sum('amount');
                $commission = $group->where('income_outcome', 'outcome')->sum('amount');

                return [
                    'date' => $group->first()->created_at->format('Y'),
                    'topup' => $topup,
                    'commission' => $commission
                ];
            });
        }

        return response()->json([
            'transactions' => $transactions->all(),
        ]);
    }


    protected $smsService;

    public function __construct(SMSService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function sendOTP(Request $request)
    {


        // // Validate phone number
        // $request->validate([
        //     'phone_number' => 'required|regex:/^(\+)[0-9]{10,15}$/',
        // ]);

        // Generate OTP (You can use any OTP generation logic here)
        $otp = mt_rand(100000, 999999);

        // Send OTP via SMS
        // $phoneNumber = $request->input('phone_number');
        // // $phoneNumber = "09798123885";

        // $success = $this->smsService->sendOTP($phoneNumber, $otp);

        // if ($success) {
        //     // OTP sent successfully
        //     return response()->json(['message' => 'OTP sent successfully'], 200);
        // } else {
        //     // Failed to send OTP
        //     return response()->json(['message' => 'Failed to send OTP'], 500);
        // }
    }
}
