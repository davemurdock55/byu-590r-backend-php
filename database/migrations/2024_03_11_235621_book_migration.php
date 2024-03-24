<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('books');

        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('series')->nullable();
            // $table->string('author_id');
            $table->string('author');
            $table->string('cover')->nullable(); // a path that points to the cover image in AWS S3
            $table->string('description')->nullable();
            // $table->integer('book_rating_id')->unique();
            $table->decimal('rating', 3, 2)->nullable(); // Decimal column with 3 total digits of which 2 are decimal places
            $table->date('date_published')->nullable(); // y/m/d
            $table->timestamps(); // creates created at, updated at, deleted at in the DB table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // if the books table already exists, drop this
        Schema::dropIfExists('books');
    }
};
