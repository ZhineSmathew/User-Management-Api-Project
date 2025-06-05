<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use App\Models\UsersAddress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a user for the profile data
        $user = User::create([
            'name' => 'home_page',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'mobile' => '9345345352',
            'dob' => '1990-01-14',
            'gender' => 'Male',
        ]);
        // Create the 'home' address for the user
        Address::create([
            'user_id' => $user->id,
            'address_type' => 'home',
            'address_details' => [
                'door/street' => '1st Main Rd,Rajiv Nagar',
                'landmark' => 'Zxy building',
                'city' => 'chennai',
                'state' => 'tamilnadu',
                'country' => 'India'
            ],
            'primary' => 'No' 
        ]);
        // Create the 'Office' address for the user
        Address::create([
            'user_id' => $user->id,
            'address_type' => 'Office',
            'address_details' => [
                'door/street' => 'west cross Rd, chinmayi Nagar',
                'landmark' => 'white cross building',
                'city' => 'Brooklyn',
                'state' => 'Newyork',
                'country' => 'USA'
            ],
            'primary' => 'No'
        ]);
    }
}
