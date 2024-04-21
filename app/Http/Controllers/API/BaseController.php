<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Aws\Sdk;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'result'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 418)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];


        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }


    public function getS3Url($path, $minutes = 10)
    {
        if (!$path) {
            return null;
        }

        // alternate way of doing all the code in the controller below this:
        // $url = Storage::disk('s3')->temporaryUrl($path, now()->addMinutes($minutes));
        // return $url;

        $s3 = Storage::disk('s3');
        if ($minutes === null) {
            $s3->setVisibility($path, "public");
            return $s3->url($path);
        }

        return $s3->temporaryUrl($path, now()->addMinutes($minutes));
    }

    // receives the request (which should have a book object in it)
    // and receives the $book we want to update
    public function formatDate($request, $book)
    {
        $datePublished = null;
        if ($request->has('date_published') && $request->input('date_published') !== null) {
            // Transform the date to YYYY-MM-DD format using Carbon
            $datePublished = Carbon::createFromFormat('Y-m-d', $request->input('date_published'))->format('Y-m-d');
            $book->date_published = $datePublished;
        }

        return $book;
    }

    // gets all the info we need before sending a book (back) to the frontend
    public function getBookInfo($id)
    {
        // create the book we send back to update the state with (using $book->id instead of the passed "$id" just to be super explicit so that it lines up with what we changed)
        $book = Book::where('id', $id)->with(['reviews', 'author'])->first();
        // Log::info($book);

        // grabbing the S3 image url (in place of the "local" type one)
        // gets /images/book_cover_img_1.png and replaces it with something like https://aws.console.fas.dfas/images/book_cover_img_1.png (or something like that)
        // if (isset($book->cover)) {
        $book->cover = $this->getS3Url($book->cover);
        // }

        // sorting reviews by ID
        $book->reviews = $book->reviews->sortBy('id');
        // Log::info($book->reviews);

        // grabbing the ratings
        $totalRating = 0;
        $ratingCount = 0;

        foreach ($book->reviews as $review) {
            $totalRating += (float)$review->pivot->rating;
            $ratingCount++;

            if (isset($review->avatar)) {
                $review->avatar = $this->getS3Url($review->avatar);
            }
        }

        $averageRating = $ratingCount > 0 ? $totalRating / $ratingCount : 0;

        // Truncate/round to 2 decimal places
        $averageRating = number_format($averageRating, 2);

        $book->overall_rating = $averageRating;
        $book->rating_count = $ratingCount;

        // Log::info("Finished adding things to the book");
        // Log::info($book);

        return $book;
    }
}
