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
                'avatar' => null,
                'password' =>  bcrypt('Password123!'), // some random password, e.g. Funnybunny1998
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
            // add a comma then new arrow to add another user to seed into the database
        ];

        User::insert($users);
    }
}
