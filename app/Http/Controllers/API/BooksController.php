<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BooksController extends Controller
{
    // GET -> /books
    // This would return all the books
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // select * from songs;
        // return songs;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    // POST -> /books/{body}
    // This is where you'd put your POST method to create a single resource
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // INSERT INTO songs VALUES.... name = $request['name']
    }

    // GET -> /books/{book_id}
    // This will give you a single book if you do this
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
