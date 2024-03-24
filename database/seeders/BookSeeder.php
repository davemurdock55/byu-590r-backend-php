<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\Book;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            [
                'title' => 'Way of Kings',
                'series' => "Stormlight Archive",
                'author' => "Brandon Sanderson",
                'cover' => "/images/book_covers/Way_of_Kings_cover.png",
                'description' => "The Way of Kings is an epic high fantasy novel written by American author Brandon Sanderson and the first book in The Stormlight Archive series. The novel was published on August 31, 2010, by Tor Books.",
                'rating' => 5,
                'date_published' => Carbon::create(2010, 8, 31),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Words of Radiance',
                'cover' => "/images/book_covers/Words_of_Radiance_cover.png",
                'series' => "Stormlight Archive",
                'description' => "Words of Radiance is an epic fantasy novel written by American author Brandon Sanderson and the second book in The Stormlight Archive series. The novel was published on March 4, 2014, by Tor Books.",
                'author' => "Brandon Sanderson",
                'rating' => 5,
                'date_published' => Carbon::create(2014, 3, 04),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Oathbringer',
                'cover' => "/images/book_covers/Oathbringer_cover.png",
                'series' => "Stormlight Archive",
                'description' => 'Oathbringer is an epic fantasy novel written by American author Brandon Sanderson and the third book in The Stormlight Archive series. It was published by Tor Books on November 14, 2017.',
                'author' => "Brandon Sanderson",
                'rating' => 5,
                'date_published' => Carbon::create(2017, 11, 14),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Rhythm of War',
                'cover' => "/images/book_covers/Rythm_of_War_cover.png",
                'series' => "Stormlight Archive",
                'description' => "Rhythm of War is an epic fantasy novel written by American author Brandon Sanderson and the fourth book in The Stormlight Archive series. It was published by Tor Books on November 17, 2020.",
                'author' => "Brandon Sanderson",
                'rating' => 5,
                'date_published' => Carbon::create(2020, 11, 17),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Wind and Truth',
                'cover' => "/images/book_covers/Wind_and_Truth_placeholder_cover.png",
                'series' => "Stormlight Archive",
                'description' => "The fifth book in the Stormlight Archive series, by Brandon Sanderson, to be released in 2024 and be the culmination to the first half arc of the series.",
                'author' => "Brandon Sanderson",
                'rating' => 5,
                'date_published' => Carbon::create(2024, 12, 06),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        Book::insert($books);
    }
}
