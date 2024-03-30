<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'rating',
        'comment',
        'created_at',
        'updated_at'
    ];

    //     public function books(): HasOne
    //     {
    //         // 'reading_list_books' is the bridge/join table between books and reading_lists
    //         // it goes: class, name_of_table_in_db/migration, id_in_books_table, id_of_books_as_written_in_book_table
    //         return $this->hasOne(Book::class, 'reading_list_books', 'reading_list_id', 'book_id');
    //         // return $this->belongsToMany(Book::class, 'reading_list_books', 'reading_list_id')->withPivot('book_id');
    //     }
    // 
    //     public function user(): HasOne
    //     {
    //         // 'reading_list_books' is the bridge/join table between books and reading_lists
    //         // it goes: class, name_of_table_in_db/migration, id_in_books_table, id_of_books_as_written_in_book_table
    //         return $this->hasOne(User::class, 'reading_list_books', 'reading_list_id', 'book_id');
    //         // return $this->belongsToMany(Book::class, 'reading_list_books', 'reading_list_id')->withPivot('book_id');
    //     }
}
