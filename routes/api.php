<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\BooksController;
use App\Http\Controllers\API\RatingsController;
use App\Http\Controllers\API\AuthorsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// If you have routing errors, maybe take this away since it gets /user
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout');
});

// anything inside auth:sanctum is not available publicly (you have to have a token or basically be logged in)
Route::middleware('auth:sanctum')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('user', 'getUser');
        Route::post('user/upload_avatar', 'uploadAvatar');
        Route::delete('user/remove_avatar', 'removeAvatar');
        Route::post('user/send_verification_email', 'sendVerificationEmail');
        Route::post('user/change_email', 'changeEmail');
        Route::post('user/add_books_to_reading_list', 'addBooksToReadingList');
        Route::post('user/{id}/remove_book_from_reading_list', 'removeBookFromReadingList');
    });

    // this links up to all the resource action stuff that we got when we made BooksController using php artisan make:controller --resource
    Route::resource('books', BooksController::class);
    Route::controller(BooksController::class)->group(function () {
        Route::post('books/{id}/update_book_cover', 'updateBookCover');
        Route::post('books/{id}/remove_book_cover', 'removeBookCover');
    });

    Route::resource('ratings', RatingsController::class);
    Route::resource('authors', AuthorsController::class);
});
