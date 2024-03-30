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
                'password' =>  bcrypt('Password123!'),
                'avatar' => "images/1710008628_1.jpeg",
                'reading_list_id' => 1,
                'email_verified_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'John Christiansen',
                'email' => 'jc12996@byu.edu',
                'password' =>  bcrypt('Funnybunny1998!'), // some random password, e.g. Funnybunny1998!
                'avatar' => "images/1710371696_2.jpeg",
                'reading_list_id' => 2,
                'email_verified_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
            // add a comma then new array to add another user to seed into the database
        ];

        User::insert($users);
    }
}
