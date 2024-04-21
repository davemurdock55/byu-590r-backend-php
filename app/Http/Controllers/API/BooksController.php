<?php

namespace App\Http\Controllers\API;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
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
        // $books = Book::orderBy('id', 'asc')->get();
        $books = Book::orderBy('id', 'asc')->with(['reviews', 'author'])->get();
        // you can do ->with to get from a many-to-many relationship

        foreach ($books as $key => $book) {
            $books[$key] = $this->getBookInfo($book->id);
        }


        // foreach ($books as $book) {
        //     $book = $this->getBookInfo($book->id);
        // }

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
        // Log::info("about to validate");
        // Log::info($request);
        //
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'author_id' => 'required',
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
        // $datePublished = null;
        // if ($request->has('date_published') && $request->input('date_published') !== null) {
        //     // Transform the date to YYYY-MM-DD format using Carbon
        //     $datePublished = Carbon::createFromFormat('Y-m-d', $request['date_published'])->format('Y-m-d');
        //     $book->date_published = $datePublished;
        // }

        $book = $this->formatDate($request, $book);


        $book->title = $request['title'];
        $book->series = $request['series'];
        $book->author_id = $request['author_id'];
        // we already got $book->cover
        $book->description = $request['description'];
        // $book->rating = $request['rating'];
        // we already got date_published (Supposedly. If that doesn't work, just remove the code for that and make it like the rest)

        $book->save();

        // if (isset($book->cover)) {
        //     $book->cover = $this->getS3Url($book->cover);
        // }
        $response_book = $this->getBookInfo($book->id);

        $success['book'] = $response_book;
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

        // $book->cover = $this->getS3Url($book->cover);
        $response_book = $this->getBookInfo($book->id);

        $success['book'] = $response_book;
        return $this->sendResponse($success, 'Book successfully retrieved.');
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
            'author_id' => 'required',
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

        // $datePublished = null;
        // if ($request->has('date_published') && $request->input('date_published') !== null) {
        //     // Transform the date to YYYY-MM-DD format using Carbon
        //     $datePublished = Carbon::createFromFormat('Y-m-d', $request->input('date_published'))->format('Y-m-d');
        //     $book->date_published = $datePublished;
        // }

        $book = $this->formatDate($request, $book);


        $book->title = $request['title'];
        $book->series = $request['series'];
        $book->author_id = $request['author_id'];
        // we already got $book->cover
        $book->description = $request['description'];
        // $book->rating = $request['rating'];
        // we already got date_published (Supposedly. If that doesn't work, just remove the code for that and make it like the rest)

        $book->save();

        // create the book we send back to update the state with (using $book->id instead of the passed "$id" just to be super explicit so that it lines up with what we changed)
        //         $response_book = Book::where('id', $book->id)->with(['reviews', 'author'])->first();
        // 
        //         // grabbing the S3 image url (in place of the "local" type one)
        //         if (isset($response_book->cover)) {
        //             $response_book->cover = $this->getS3Url($response_book->cover);
        //         }
        // 
        //         // grabing the ratings
        //         $totalRating = 0;
        //         $ratingCount = 0;
        // 
        //         foreach ($response_book->reviews as $review) {
        //             $totalRating += (float)$review->pivot->rating;
        //             $ratingCount++;
        //         }
        // 
        //         $averageRating = $ratingCount > 0 ? $totalRating / $ratingCount : 0;
        // 
        //         $response_book->overall_rating = $averageRating;
        //         $response_book->rating_count = $ratingCount;

        $response_book = $this->getBookInfo($book->id);

        // setting the response_book into the response's 'book' so that we don't have to change the frontend
        $success['book'] = $response_book;
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

        $response_book = $this->getBookInfo($book->id);

        $success['book'] = $response_book;
        return $this->sendResponse($success, 'Book cover successfully updated.');
    }

    public function removeBookCover(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        Storage::disk('s3')->delete($book->cover);
        $book->cover = null;
        $book->save();

        $response_book = $this->getBookInfo($book->id);

        $success['book'] = $response_book;
        return $this->sendResponse($success, 'Book cover removed successfully.');
    }

    public function addReview(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:books,id',
            'newReview.rating' => 'required|numeric|min:0|max:5',
            'newReview.comment' => 'nullable|string',
        ]);

        $book = Book::findOrFail($validatedData['id']);
        $authUser = Auth::user();

        $review = new Review([
            'user_id' => $authUser->id,
            'book_id' => $book->id, // Set the book_id here
            'rating' => $validatedData['newReview']['rating'],
            'comment' => $validatedData['newReview']['comment'] ?? null,
        ]);

        $review->save(); // Save the review
        // Log::info($review);
        // Log::info($review->id);

        $responseBook = $this->getBookInfo($book->id);
        // Log::info($responseBook);

        return $this->sendResponse($responseBook, 'Review added successfully.');
    }




    public function removeReview(Request $request, $id)
    {
        // Log::info($id);

        $review = Review::findOrFail($id);
        // Log::info($review);

        $book = Review::findOrFail($review->book_id);

        $review->delete();

        $responseBook = $this->getBookInfo($book->id);
        return $this->sendResponse($responseBook, 'Review added successfully.');
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

        // Delete all reviews associated with the book
        $book->reviews()->detach();

        // Remove the book from all reading lists it is part of
        $book->reading_lists()->detach();


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
