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
        Schema::dropIfExists('publishers');
        // id
        // publisher name
        Schema::create('publishers', function (Blueprint $table) {
            $table->id();
            $table->string('publisher_name');
            $table->timestamps(); // creates created at, updated at, deleted at in the DB table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('publishers');
    }
};
