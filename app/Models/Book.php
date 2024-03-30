<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'series',
        'cover',
        'description',
        // 'overall_rating',
        'date_published',
        'created_at',
        'updated_at'
    ];

    public function author(): HasOne
    {
        // 'reading_list_books' is the bridge/join table between books and authors
        return $this->hasOne(Author::class, 'id', 'author_id');
        // if that doesn't work, try something like the below
        // it goes: author class, id_in_authors_table, id_of_authors_as_written_in_book_table
        // return $this->hasOne(Author::class, 'id', 'author_id');
    }

    public function reviews(): BelongsToMany
    {
        // 'reading_list_books' is the bridge/join table between books and reading_lists
        // return $this->belongsToMany(Review::class, 'reviews', 'book_id', 'review_id');
        return $this->belongsToMany(User::class, 'reviews', 'book_id', 'user_id')->withPivot('rating', 'comment');
    }

    // public function readingLists(): BelongsToMany
    // {
    //     return $this->belongsToMany(ReadingList::class, 'reading_list_books', 'book_id', 'reading_list_id');
    // }

    public function reading_lists(): BelongsToMany
    {
        return $this->belongsToMany(ReadingList::class, 'reading_list_books', 'book_id', 'reading_list_id');
    }


    // public function reviews(): HasMany
    // {
    //     // 'reading_list_books' is the bridge/join table between books and reading_lists
    //     return $this->hasMany(Review::class, 'reviews', 'book_id', 'review_id');
    // }

    // public function reading_lists(): BelongsToMany
    // {
    //     // 'reading_list_books' is the bridge/join table between books and reading_lists
    //     // it goes: class, name_of_table_in_db/migration, id_in_reading_lists_table, id_of_reading_lists_as_written_in_book_table
    //     return $this->belongsToMany(ReadingList::class, 'reading_list_books', 'book_id', 'reading_list_id');
    //     // return $this->belongsToMany(ReadingList::class, 'reading_list_books', 'book_id')->withPivot('reading_list_id');
    // }
}
