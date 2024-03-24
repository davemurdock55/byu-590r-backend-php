<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = [
            [
                'name' => 'David Murdock',
                'email' => 'davemurdock55@gmail.com',
                'email_verified_at' => null,
                'avatar' => "images/1710008628_1.jpeg",
                'password' =>  bcrypt('Password123!'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'John Christiansen',
                'email' => 'jc12996@byu.edu',
                'email_verified_at' => null,
                'avatar' => "images/1710371696_2.jpeg",
                'password' =>  bcrypt('Funnybunny1998!'), // some random password, e.g. Funnybunny1998!
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
            // add a comma then new array to add another user to seed into the database
        ];

        User::insert($users);
    }
}
