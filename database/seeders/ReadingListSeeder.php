<?php

namespace Database\Seeders;

use App\Models\ReadingList;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class ReadingListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // make a list of lists of data to seed (each list is an row/entity and we have a list to contain them)
        $reading_lists = [
            [
                'user_id' => 1,
                'name' => "David's Reading List",
                'description' => "These are all the upcoming books I want to read.",
                'status' => "incomplete",
            ],
            [
                'user_id' => 2,
                'name' => "Professor Christiansen's Reading List",
                'description' => "These are all the upcoming books I want to read.",
                'status' => "incomplete",
            ],
        ];

        ReadingList::insert($reading_lists);
    }
}
