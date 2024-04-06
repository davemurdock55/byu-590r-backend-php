<?php

namespace App\Http\Controllers\API;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    // maps to the "get()" function in axios if no /# parameter is added
    // gets ALL
    public function index()
    {
        $authors = Author::orderBy('name', 'desc')->get();

        return $this->sendResponse($authors, "books retrieved!");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // CREATE
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    // gets ONE resource
    public function show(string $id)
    {
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
    // UPDATE
    // he removed string before $id in the parameters (it's a type, so it not in quotes)
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    // DELETE
    public function destroy($id)
    {
    }
}
