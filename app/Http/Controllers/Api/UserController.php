<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        $users = User::with('addresses')->paginate(10);

        return response()->json([
            'status_code' => 200,
            'message' => 'User list',
            'data' => $users->map(function ($user) {
                return [
                    'user_name' => $user->name,
                    'mobile' => $user->mobile,
                    'dob' => $user->dob,
                    'gender' => $user->gender,
                    'Address' => $user->addresses->map(function ($address, $index) {
                        return [
                            'address_type' => $address->address_type,
                            $index === 0 ? 'address1' : 'address2' => [
                                'door/street' => $address->address_details['door/street'],
                                'landmark' => $address->address_details['landmark'],
                                'city' => $address->address_details['city'],
                                'state' => $address->address_details['state'],
                                'country' => $address->address_details['country'],
                            ],
                            'primary' => $address->primary
                        ];
                    }),
                ];
            }),
            'pagination' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
            ]
        ]);
    }

    public function show($id)
    {
        $usersDetails = User::with('addresses')->find($id);

        if (!$usersDetails) {
            return response()->json([
                'status_code' => 404,
                'message' => 'User not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status_code' => 200,
            'message' => 'User details',
            'data' => [
                'user_name' => $usersDetails->name,
                'mobile' => $usersDetails->mobile,
                'dob' => $usersDetails->dob,
                'gender' => $usersDetails->gender,
                'Address' => $usersDetails->addresses->map(function ($address, $index) { //retrieves the list of addresses from userdetails
                    return [
                        'address_type' => $address->address_type,
                        $index === 0 ? 'address1' : 'address2' => [ // assign name for each array object
                            'door/street' => $address->address_details['door/street'],
                            'landmark' => $address->address_details['landmark'],
                            'city' => $address->address_details['city'],
                            'state' => $address->address_details['state'],
                            'country' => $address->address_details['country'],
                        ],
                        'primary' => $address->primary
                    ];
                })
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'mobile' => 'required|string',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'addresses' => 'required|array|min:1',
            'addresses.*.address_type' => 'required|string',
            'addresses.*.address_details' => 'required|array',
            'addresses.*.primary' => 'required|string|in:Yes,No',
        ]);
        // dd($request->all());
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'mobile' => $request->mobile,
            'dob' => $request->dob,
            'gender' => $request->gender,
        ]);
        // save address data to the addresses table using Eloquent relationship 
        foreach ($request->addresses as $address) {
            $user->addresses()->create([
                'address_type' => $address['address_type'],
                'address_details' => $address['address_details'],
                'primary' => $address['primary'],
            ]);
        }

        return response()->json([
            'status_code' => 201,
            'message' => 'User created successfully',
            'data' => $user->load('addresses'),
        ]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'mobile' => 'required|string',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'addresses' => 'required|array|min:1',
            'addresses.*.address_type' => 'required|string',
            'addresses.*.address_details' => 'required|array',
            'addresses.*.primary' => 'required|string|in:Yes,No',
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status_code' => 404,
                'message' => 'User not found',
                'data' => null
            ], 404);
        }

        // Update user fields
        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'mobile' => $validatedData['mobile'],
            'dob' => $validatedData['dob'],
            'gender' => $validatedData['gender'],
        ]);

        // remove old and create new ones
        $user->addresses()->delete();

        foreach ($validatedData['addresses'] as $address) {
            $user->addresses()->create($address);
        }

        return response()->json([
            'status_code' => 200,
            'message' => 'User updated successfully',
            'data' => $user->load('addresses')
        ]);
    }


    public function destroy($id)
    {
        $user = User::with('addresses')->find($id);

        if (!$user) {
            return response()->json([
                'status_code' => 404,
                'message' => 'User not found',
            ], 404);
        }

        $user->delete(); // delete user and its address data 

        return response()->json([
            'status_code' => 200,
            'message' => 'User deleted successfully',
        ]);
    }


}
