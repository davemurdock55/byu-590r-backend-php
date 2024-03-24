<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'series',
        'author',
        'cover',
        'description',
        'rating',
        'date_published',
        'created_at',
        'updated_at'
    ];

    //     public function authors(): BelongsToMany {
    //     // 'book_authors' is the bridge/join table between books and authors
    //         return $this->belongsToMany(Author::class, 'book_authors', 'book_id')->withPivot('author_id');
    //     }
    // 
    //     public function reading_lists(): BelongsToMany
    //     {
    //         // 'reading_list_books' is the bridge/join table between books and authors
    //         return $this->belongsToMany(ReadingList::class, 'reading_list_books', 'book_id')->withPivot('reading_list_id');
    //     }
    // 
    //     public function reviews(): BelongsToMany
    //     {
    //         // 'book_reviews' is the bridge/join table between books and authors
    //         return $this->belongsToMany(Review::class, 'book_reviews', 'book_id')->withPivot('review_id');
    //     }


    // Don't actually do the below because Books and Series have a Many to One relationship.
    // The only one to one relationship I have is the user to reading list.
    // if you have done all your naming conventions correctly, then this should work
    // just comment out 'series' in the $fillable list above
    // public function series(): HasOne {
    //     return $this->hasOne(Series::class)
    // }

    // If that doesn't work, then something about the naming might be off and you can do this insteadd
    // you may not be abel to add the table name in here (e.g. series_table)
    // public function series(): HasOne {
    //     return $this->hasOne(Series::class, 'series_table', 'id_of_series_table', 'series_id_in_book_table')
    // }
}
