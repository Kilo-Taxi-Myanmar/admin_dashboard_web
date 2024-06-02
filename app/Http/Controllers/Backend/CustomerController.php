<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\Storage;


class CustomerController extends Controller
{
    public function index(){
          // Use the query builder to construct the SQL query
          $usersQuery = User::role('customer');

          
          // Get the total count of all users
          $usersCount = $usersQuery->count();
  
          // Paginate the results
          $users = $usersQuery->paginate(25);
        // dd($users);
        return view('backend.customers.index',compact('users','usersCount','usersQuery'));
    }
    public function create(){
        return view('backend.customers.create');

    }
    public function store(Request $request){

        

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone|max:255',
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'email' => 'nullable|email|unique:users,email|max:255',
            'address' => 'nullable|string|max:255',
           
        ]);

        try {
            $user = User::create([
                'name' => $validatedData['first_name'].' '.$validatedData['last_name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                // 'birth_date' => $validatedData['birth_date'],
                'address' => $validatedData['address'],
                // 'nrc_no' => $validatedData['nrc_no'],
                // 'driving_license' => $validatedData['driving_license'],
                'password' => Hash::make($validatedData['password']),
                'status' => 'active',
            ])->assignRole('customer');
            $user->driver_id = sprintf('%04d', $user->id - 1);
            $user->save();

           

        } catch (ValidationException $e) {
            return back()->withErrors($e->getMessage());
        }

        return redirect()->route('customers.index');


    }

    public function edit(User $user){
        return view('backend.customers.edit', compact('user'));

    }

    public function update(Request $request,$id){

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',           
            'phone' => 'required|string|max:255|unique:users,phone,'.$id,
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'email' => ['nullable','email','unique:users,email,'.$id,'max:255'],
            'address' => 'nullable|string|max:255',
           
        ]);

        try {
            $user = User::find($id);

        // Update the user's attributes
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->phone = $validatedData['phone'];
        $user->address = $validatedData['address'];
        $user->password = Hash::make($validatedData['password']);
        $user->save();
        } catch (ValidationException $e) {
            return back()->withErrors($e->getMessage());
        }

        return redirect()->route('customers.index');

    }

    public function destroy($id)
    {
        // dd($id);
        $user = User::findOrFail($id);
    
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

       
        $user->tokens()->delete();
        $user->delete();
        return redirect()->route('customers.index');
    }


    public function search(Request $request)
    {
        $key = $request->input('key');

        // Use the query builder to construct the SQL query
        $usersQuery = User::role('customer')
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

        return view('backend.customers.index', compact('users', 'usersCount'));
    }

    
    public function show($id)
    {

        // dd($id);
        $user = User::findOrFail($id);
        $transactions = $user->transactions()
            ->where('income_outcome', 'income')->latest()
            ->paginate(10);

        $tripsQuery = $user->trips();
        $tripsCount = $tripsQuery->count();
        $trips = $tripsQuery->latest()->paginate(10);

        return view('backend.customers.show', compact('user', 'transactions', 'trips', 'tripsCount'));
    }
}
