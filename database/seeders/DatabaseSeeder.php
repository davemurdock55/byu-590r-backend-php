<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\ReadingList;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // this is a specific order it runs in
        // think about what depends on what
        $this->call([
            UsersSeeder::class,
            AuthorSeeder::class,
            BookSeeder::class,
            ReviewSeeder::class,
            ReadingListBooksSeeder::class,
            ReadingListSeeder::class, // Change ReadingList to ReadingListSeeder
        ]);
    }
}
