<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReadingListBooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $reading_list_books = [
            [
                'reading_list_id' => 1,
                'book_id' => 1,
            ],
            [
                'reading_list_id' => 1,
                'book_id' => 2,
            ],
            [
                'reading_list_id' => 1,
                'book_id' => 3,
            ],
            [
                'reading_list_id' => 1,
                'book_id' => 4,
            ],
            [
                'reading_list_id' => 1,
                'book_id' => 5,
            ],

            [
                'reading_list_id' => 2,
                'book_id' => 1,
            ],
        ];

        // NEED TO ADD THE INSERT STATEMENT
        DB::table('reading_list_books')->insert($reading_list_books);
    }
}
