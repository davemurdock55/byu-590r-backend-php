<?php

namespace App\Http\Controllers\API;

use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;


class UserController extends BaseController
{
	public function getUser()
	{
		$authUser = Auth::user();
		// trying to use a 'with' and 'get()' on findOrFail
		$user = User::findOrFail($authUser->id); // findOrFail gets the first object in the database where the id is equal to the id you're logged in as and return the first it finds (if it doesn't find anything, it fails)

		// Load the 'reading_list' relationship with its 'books' relationship
		// 		$user->load('readingList.books.author');
		// 
		// 		// Load the 'reading_list' relationship with its 'books' relationship
		// 		$user->load('readingList.books.author', 'readingList.books.reviews');

		$user->load([
			'readingList.books' => function ($query) {
				$query->orderBy('reading_list_books.id');
			},
			'readingList.books.author',
			'readingList.books.reviews',
		]);

		// Modify each book to get the S3 URL for the cover image and calculate ratings

		foreach ($user->readingList->books as $book) {
			$book->cover = $this->getS3Url($book->cover);

			$totalRating = 0;
			$ratingCount = 0;

			// Calculate total rating and count
			foreach ($book->reviews as $review) {
				$totalRating += (float)$review->pivot->rating;
				$ratingCount++;
			}

			// Calculate average rating
			$averageRating = $ratingCount > 0 ? $totalRating / $ratingCount : 0;

			// Set overall_review and rating_count attributes for the book
			$book->overall_rating = $averageRating;
			$book->rating_count = $ratingCount;
		}

		// THIS should definitely work and should also pull in the reading list as well :)
		// $user = User::where($authUser->id, 'id')->with(['reading_list'])->first();

		$user->avatar = $this->getS3Url($user->avatar); // getS3Url is combining the aws bucket url and its hash with the rest of the path (stored in "avatar") and temporarily overwriting that by doing user->avatar

		return $this->sendResponse($user, 'User');
	}

	public function uploadAvatar(Request $request)
	{

		$request->validate([
			'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
		]);


		if ($request->hasFile('image')) {

			$authUser = Auth::user();

			$user = User::findOrFail($authUser->id);

			$extension = request()->file('image')->getClientOriginalExtension();

			$image_name = time() . '_' . $authUser->id . '.' . $extension;

			$path = $request->file('image')->storeAs(
				'images',
				$image_name,
				's3'
			);

			Storage::disk('s3')->setVisibility($path, "public");

			if (!$path | $path === "") {
				return $this->sendError($path, 'User Profile avatar failed to upload!');
			}

			// if the user already has an avatar, then go and delete it.
			if ($user->avatar !== null) {
				Storage::disk('s3')->delete($user->avatar);
			}

			$user->avatar = $path;
			$user->save();
			$success['avatar'] = null;
			if (isset($user->avatar)) {
				$success['avatar'] = $this->getS3Url($path);
			}
			return $this->sendResponse($success, 'User profile avatar uploaded successfully!');
		}
	}

	public function removeAvatar()
	{
		$authUser = Auth::user();
		$user = User::findOrFail($authUser->id);
		Storage::disk('s3')->delete($user->avatar);
		$user->avatar = null;
		$user->save();
		$success['avatar'] = null;
		return $this->sendResponse($success, 'User profile avatar removed successfully.');
	}

	public function addBooksToReadingList(Request $request)
	{
		$authUser = Auth::user();
		$readingList = $authUser->readingList;

		if ($readingList) {
			Log::info($request->all());

			$bookObjects = $request->all();
			$bookIds = array_column($bookObjects, 'id');

			if (is_array($bookIds) && !empty($bookIds)) {
				// Get the existing book IDs associated with the reading list
				$existingBookIds = $readingList->books()->pluck('books.id')->toArray();

				// Filter out the existing book IDs from the new book IDs
				$newBookIds = array_diff($bookIds, $existingBookIds);

				// Attach the new book IDs to the reading list
				if (!empty($newBookIds)) {
					$readingList->books()->attach($newBookIds);
				}
			}
		}

		// Additional logic or return a response
	}
}
