<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// base URL of the website
define("BASEURL", "/seminarska2023/public");

// display homepage on which user selects a video
Route::get('/', [VideoController::class, 'homepage']);

Route::get('/users/registrationForm', [UserController::class, 'registrationForm'])->middleware('guest');

// add new user to DB
Route::post('/users/register', [UserController::class, 'register'])->middleware('guest');

Route::get('/users/verifyMail', [UserController::class, 'verifyMail']);//->middleware('auth');

// display form to user to log in
Route::get('/users/loginForm', [UserController::class, 'loginForm'])->middleware('guest');

// log user in
Route::post('/users/login', [UserController::class, 'login'])->middleware('guest');
    //->middleware('throttle:fourPerMinute');

Route::post('/users/logout', [UserController::class, 'logout'])->middleware('auth');

// display a requested foreign profile to a user
Route::get('/users/profile/{user}', [UserController::class, 'foreignProfile']);

// display user's own profile to a user
Route::get('/users/selfProfile', [UserController::class, 'selfProfile'])->middleware('auth');//, 'verified');

// update user's profile (channel description, ...)
Route::put('/users/profile/update', [UserController::class, 'updateProfile'])->middleware('auth');

// show form for uploading a video
Route::get('/users/uploadForm', [UserController::class, 'uploadForm'])->middleware('auth');

// store new user's video to database
Route::post('/users/store', [UserController::class, 'store'])->middleware('auth');

// // send a page that contains the video tag with source of the selected video
Route::get('/videos/watch/{video}', [VideoController::class, 'watch']);

// send part of video from requested starting point
Route::get('/videos/chunk/{video}', [VideoController::class, 'serveChunk']);

// returns a gif for which browser sent fetch
Route::get('/assets/gif/{slug}', [AssetController::class, 'fetchGif']);


/*Auth::routes([
    'verify' => true
]);*/

//Auth::routes();

//SRoute::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
