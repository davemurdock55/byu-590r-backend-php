<?php

namespace App\Http\Controllers\API;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class BooksController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    // maps to the "get()" function in axios if no /# parameter is added
    // gets ALL
    public function index()
    {

        // gets all books
        // $books = Book::get(); // could also do ::all(), but less useful???
        // gets all the books ordered by title in ascending order
        $books = Book::orderBy('id', 'asc')->get();
        // $books = Book::orderBy('id', 'asc')->with(['series.authors'])->get();
        // you can do ->with to get from a many-to-many relationship

        foreach ($books as $book) {
            // gets /images/book_cover_img_1.png and replaces it with something like https://aws.console.fas.dfas/images/book_cover_img_1.png (or something like that)
            $book->cover = $this->getS3Url($book->cover);
        }

        return $this->sendResponse($books, "books retrieved!");
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
        //
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'author' => 'required',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg', // he put 'required|' at the start like this: 'required|image|mimes:jpeg,png,jpg,gif,svg'
            'date_published' => 'nullable|date|date_format:Y-m-d', // Add date validation with format
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $book = new Book;

        if ($request->hasFile('cover')) {
            $extension = request()->file('cover')->getClientOriginalExtension();
            $image_name = time() . '_book_cover.' . $extension;
            // if you run into problems, try changing 'images/book_covers' to just 'images'
            $path = $request->file('cover')->storeAs(
                'images/book_covers',
                $image_name,
                's3'
            );

            Storage::disk('s3')->setVisibility($path, 'public');
            if (!$path) {
                return $this->sendError($path, 'Book cover failed to upload.');
            }

            $book->cover = $path;
        }

        // Handle date formatting and transformation
        $datePublished = null;
        if ($request->has('date_published') && $request->input('date_published') !== null) {
            // Transform the date to YYYY-MM-DD format using Carbon
            $datePublished = Carbon::createFromFormat('Y-m-d', $request['date_published'])->format('Y-m-d');
            $book->date_published = $datePublished;
        }


        $book->title = $request['title'];
        $book->series = $request['series'];
        $book->author = $request['author'];
        // we already got $book->cover
        $book->description = $request['description'];
        $book->rating = $request['rating'];
        // we already got date_published (Supposedly. If that doesn't work, just remove the code for that and make it like the rest)

        $book->save();

        if (isset($book->cover)) {
            $book->cover = $this->getS3Url($book->cover);
        }

        $success['book'] = $book;
        return $this->sendResponse($success, 'Book successfully added.');
    }



    /**
     * Display the specified resource.
     */
    // gets ONE resource
    public function show(string $id)
    {
        //
        // $books = Book::where('id', $id)->with(['series.authors'])->first();
        $book = Book::where('id', $id)->first();

        $book->cover = $this->getS3Url($book->cover);

        return $this->sendResponse($book, 'book');
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
        //
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'author' => 'required',
            // 'date_published' => 'nullable|date_format:Y-m-d', // Check date format
        ]);

        if ($validator->fails()) {

            return $this->sendError('Validation Error.', $validator->errors());
        }

        $book = Book::findOrFail($id);


        if ($request->hasFile('cover')) {
            $extension = request()->file('cover')->getClientOriginalExtension();
            $image_name = time() . '_book_cover.' . $extension;
            // if you run into problems, try changing 'images/book_covers' to just 'images'
            $path = $request->file('cover')->storeAs(
                'images/book_covers',
                $image_name,
                's3'
            );

            Storage::disk('s3')->setVisibility($path, 'public');
            if (!$path) {
                return $this->sendError($path, 'Book cover failed to upload.');
            }

            $book->cover = $path;
        }

        $datePublished = null;
        if ($request->has('date_published') && $request->input('date_published') !== null) {
            // Transform the date to YYYY-MM-DD format using Carbon
            $datePublished = Carbon::createFromFormat('Y-m-d', $request->input('date_published'))->format('Y-m-d');
            $book->date_published = $datePublished;
        }


        $book->title = $request['title'];
        $book->series = $request['series'];
        $book->author = $request['author'];
        // we already got $book->cover
        $book->description = $request['description'];
        $book->rating = $request['rating'];
        // we already got date_published (Supposedly. If that doesn't work, just remove the code for that and make it like the rest)

        $book->save();

        if (isset($book->cover)) {
            $book->cover = $this->getS3Url($book->cover);
        }

        $success['book'] = $book;
        return $this->sendResponse($success, 'Book successfully updated.');
    }

    public function updateBookCover(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'cover' => 'required',
                // 'cover' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            ]
        );

        if ($validator->fails()) {
            return $this->sendError('File Type Error.', $validator->errors());
        }

        $book = Book::findOrFail($id);

        if ($request->hasFile('cover')) {
            $extension = request()->file('cover')->getClientOriginalExtension();
            $image_name = time() . '_book_cover.' . $extension;
            // if you run into problems, try changing 'images/book_covers' to just 'images'
            $path = $request->file('cover')->storeAs(
                'images/book_covers',
                $image_name,
                's3'
            );

            Storage::disk('s3')->setVisibility($path, 'public');
            if (!$path) {
                return $this->sendError($path, 'Book cover failed to upload.');
            }

            $book->cover = $path;
        }

        $book->save();

        if (isset($book->cover)) {
            $book->cover = $this->getS3Url($book->cover);
        }

        $success['book'] = $book;
        return $this->sendResponse($success, 'Book cover successfully updated.');
    }

    public function removeBookCover(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        Storage::disk('s3')->delete($book->cover);
        $book->cover = null;
        $book->save();

        $success['book'] = $book;

        return $this->sendResponse($success, 'Book cover removed successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    // DELETE
    public function destroy($id)
    {
        // Log::info("Here's the id before: " . $id);
        //
        $book = Book::findOrFail($id);

        if ($book->cover) {
            Storage::disk('s3')->delete($book->cover);
        }


        $book->delete();


        $success['book']['id'] = $id;

        return $this->sendResponse($success, 'Book deleted.');
    }

    //     public function destroy($id)
    //     {
    //         Log::info('Made it to destroy.');
    //         Log::info("Here's the id: $id");
    // 
    //         // Find the book by ID
    //         $book = Book::findOrFail($id);
    // 
    //         // Delete the book cover from storage if it exists
    //         if ($book->cover) {
    //             Storage::disk('s3')->delete($book->cover);
    //         }
    // 
    //         // Delete the book record from the database
    //         $book->delete();
    // 
    //         // Return success response
    //         return $this->sendResponse(['book' => $book->toArray()], 'Book deleted.');
    //     }
}
