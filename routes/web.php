<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
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

// display homepage on which user selects a video
Route::get('/', [VideoController::class, 'loadHomepage']);

// display a requested profile to a user
Route::get('/profile/{user}', [UserController::class, 'usersProfile']);

// show form for uploading a video
Route::get('videos/uploadForm', [UserController::class, 'uploadForm']);

// store new user's video to database
Route::get('videos/store', [UserController::class, 'store']);

// // send a page that contains the video tag with source of the selected video
Route::get('/videos/{video}/play', [VideoController::class, 'play']);
