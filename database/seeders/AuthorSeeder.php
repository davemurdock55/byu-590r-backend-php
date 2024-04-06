<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;


class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = [
            [
                'name' => 'Brandon Sanderson',
                'description' => 'Brandon Sanderson is an American author of high fantasy and science fiction. He is best known for the Cosmere fictional universe, in which most of his fantasy novels, most notably the Mistborn series and The Stormlight Archive, are set.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Dan Wells',
                'description' => "Daniel Andrew Wells is an American horror and science fiction author. Wells's first published novel, I Am Not a Serial Killer, was adapted into a movie in 2016.",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'J. K. Rowling',
                'description' => "Joanne Rowling CH OBE FRSL, better known by her pen name J. K. Rowling, is a British author and philanthropist. She wrote Harry Potter, a seven-volume fantasy series published from 1997 to 2007.",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Rick Riordan',
                'description' => "Richard Russell Riordan Jr. is an American author, best known for writing the Percy Jackson & the Olympians series. Riordan's books have been translated into forty-two languages and sold more than thirty million copies in the United States.",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        //
        Author::insert($authors);
    }
}
