<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;

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

// php-cgi.exe -b 127.0.0.1:9000
// https://127.0.0.1/teachers/mainpage

// base URL of the website
//define("BASEURL", "/seminarska2023/public");
define("BASEURL", "");
define("WEBSOCKET_URL", "wss://192.168.64.100:443/robots/");

// display homepage on which user selects a video
//Route::get('/', [VideoController::class, 'homepage']);
//Route::get('/', [UserController::class, 'loginForm'])->middleware('guest');
Route::get('/', [UserController::class, 'mainPage']);

//Route::get('/', [UserController::class, 'redirectToMainpage'])->middleware('auth')
//    ->where(auth()->user()!=null);

Route::get('/users/registrationForm/{actor}', [UserController::class, 'registrationForm'])->middleware('guest');

// add new user to DB
Route::post('/users/register/{actor}', [UserController::class, 'register'])->middleware('guest');

Route::get('/users/verifyMail', [UserController::class, 'verifyMail'])->middleware('guest');

// deletes user on his request before verification was made
Route::get('/users/deleteBeforeVerified', [UserController::class, 'deleteBeforeVerified'])->middleware('guest');

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

Route::get('/users/deleteVideo', [UserController::class, 'deleteVideo'])->middleware('auth');

// show form for uploading a video
Route::get('/users/uploadForm', [UserController::class, 'uploadForm'])->middleware('auth');

// store new user's video to database
Route::post('/users/store', [UserController::class, 'store'])->middleware('auth');

Route::get('/videos/courseVideos/{course}', [VideoController::class, 'courseVideos'])->middleware('auth');

// // send a page that contains the video tag with source of the selected video
Route::get('/videos/watch/{video}/{course}', [VideoController::class, 'watch'])->middleware('auth');   // pri navadnem streamingu ni bilo auth

// send part of video from requested starting point
Route::get('/videos/chunk/{video}', [VideoController::class, 'serveChunk'])->middleware('auth');

//Route::post('/teachers/register/{actor}', [TeacherController::class, 'register']);

Route::get('/teachers/mainpage', [TeacherController::class, 'mainpage'])->middleware('auth');

Route::get('/teachers/selfProfile', [TeacherController::class, 'selfProfile'])->middleware('auth');

// shows form for uploading a video
Route::get('/teachers/uploadForm/{course}', [TeacherController::class, 'uploadForm'])->middleware('auth');

Route::post('/teachers/store/{course}', [TeacherController::class, 'store'])->middleware('auth');

Route::get('/teachers/deleteVideo', [TeacherController::class, 'deleteVideo'])->middleware('auth');

Route::get('/teachers/studentPermissions', [TeacherController::class, 'studentPermissions'])->middleware('auth');

Route::post('/teachers/addAllowedEmail', [TeacherController::class, 'addAllowedEmail']);//->middleware('auth');

Route::post('/teachers/removeAllowedEmail', [TeacherController::class, 'removeAllowedEmail']);//->middleware('auth');

Route::get('/students/mainpage', [StudentController::class, 'mainpage'])->middleware('auth');

Route::get('/students/enrollment/request/{course}', [StudentController::class, 'requestEnrollment'])->middleware('auth');

Route::get('/students/enrollment/delete/{course}', [StudentController::class, 'deleteEnrollmentRequest'])->middleware('auth');

Route::get('/teachers/enrollStudent', [TeacherController::class, 'enrollStudent'])->middleware('auth');

Route::get('/teachers/coursePage/{course}', [TeacherController::class, 'coursePage'])->middleware('auth');

Route::get('/teachers/checkIp/{course}', [TeacherController::class, 'checkIp'])->middleware('auth');

Route::post('/teachers/submitPresentStudents/{course}', [TeacherController::class, 'submitPresentStudents']);

Route::get('/students/coursePage/{course}', [StudentController::class, 'coursePage'])->middleware('auth');

Route::get('/students/statistics', [StudentController::class, 'statistics'])->middleware('auth');

Route::get('/students/settings', [StudentController::class, 'settings'])->middleware('auth');

Route::get('/students/ipForm', [StudentController::class, 'showIpForm'])->middleware('auth');

Route::get('/students/addIp', [StudentController::class, 'addIp'])->middleware('auth');

Route::get('/students/ipChecking', [StudentController::class, 'ipChecking'])->middleware('auth');

Route::get('/students/ipCheckingSuccess', [StudentController::class, 'ipCheckingSuccess'])->middleware('auth');

Route::get('/teachers/revertIpChecking', [TeacherController::class, 'revertIpChecking'])->middleware('auth');

Route::get('courses/search', [CourseController::class, 'search'])->middleware('auth');
    //->where(auth()->user()->isTeacher, '0');
Route::get('teachers/newCourseForm', [TeacherController::class, 'newCourseForm'])->middleware('auth');
/*Route::get('courses/search', [TeacherController::class, 'mainpage'])->middleware('auth')
    ->where(auth()->user()->isTeacher, '1');*/

Route::post('courses/create', [CourseController::class, 'create'])->middleware('auth');

Route::get('/test', [TeacherController::class, 'test']);

Route::get('/test2', [TeacherController::class, 'test2']);
/*
// returns a gif for which browser sent fetch
Route::get('/assets/gif/{slug}', [AssetController::class, 'fetchGif']);
*/

/*Auth::routes([
    'verify' => true
]);*/

//Auth::routes();

//SRoute::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
