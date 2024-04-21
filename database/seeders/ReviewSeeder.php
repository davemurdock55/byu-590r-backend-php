<?php

namespace Database\Seeders;

use App\Models\Review;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;


class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $reviews = [
            [
                'user_id' => 1,
                'book_id' => 1,
                'rating' => 5,
                'comment' => 'Great book!',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 1,
                'book_id' => 2,
                'rating' => 5,
                'comment' => 'Mmmm, good liesssss',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 1,
                'book_id' => 3,
                'rating' => 5,
                'comment' => 'Oathbringer is the peak of Stormlight',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 1,
                'book_id' => 4,
                'rating' => 5,
                'comment' => 'Rhythm of war has some great action sequences.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 1,
                'book_id' => 5,
                'rating' => 5,
                'comment' => 'I literally cannot wait for this book to come out! 5 out of 5 stars already, haha!',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            [
                'user_id' => 2,
                'book_id' => 2,
                'rating' => 5,
                'comment' => '"I am a stick" was hilarious!',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        Review::insert($reviews);
    }
}
