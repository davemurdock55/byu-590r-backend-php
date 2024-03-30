<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ReadingList extends Model
{
    // if auto-association isn't working, do public $table = 'name_you_came_up_with'
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'status',
    ];

    // public function books(): BelongsToMany
    // {
    //     // 'reading_list_books' is the bridge/join table between books and reading_lists
    //     // it goes: class, name_of_table_in_db/migration, id_in_books_table, id_of_books_as_written_in_book_table
    //     return $this->belongsToMany(Book::class, 'reading_list_books', 'reading_list_id', 'book_id');
    //     // return $this->belongsToMany(Book::class, 'reading_list_books', 'reading_list_id')->withPivot('book_id');
    // }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'reading_list_books', 'reading_list_id', 'book_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
