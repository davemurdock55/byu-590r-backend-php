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
        return $this->belongsToMany(User::class, 'reviews', 'book_id', 'user_id')->withPivot('id', 'user_id', 'rating', 'comment')->withTimestamps();
    }


    public function reading_lists(): BelongsToMany
    {
        return $this->belongsToMany(ReadingList::class, 'reading_list_books', 'book_id', 'reading_list_id');
    }
}
