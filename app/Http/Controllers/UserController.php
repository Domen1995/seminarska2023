<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function registrationForm()
        // show a form for creating an account
    {
        return view('users.registrationForm');
    }


    public function register(Request $request)
    {
        $formData = $request->validate([
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'name' => ['required', Rule::unique('users', 'name')],   // nickname
            'password' => ['required', 'min:7']
        ]);
        $formData['password'] = bcrypt($formData['password']);  // hash password

        $user = User::create($formData);

        auth()->login($user);
        return redirect('/');
    }

    public function loginForm()
        // display form to user to log in
    {
        return view('users.loginForm');
    }

    public function login(Request $request)
        // log user in
    {
        /*
        $user = User::find(2);
        auth()->login($user);
        return redirect('/');*/
        $formData = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);
        /*
        if(auth()->attempt($formData)){
            $request->session()->regenerate();
            return "true";
        }
        $formData['id'] = 1;
        */
        // query DB to check if email & password combination valid
        /*$formData['password'] = bcrypt($formData['password']);   // hash password
        $user = User::where('email', $formData['email'])
            ->where('password', $formData['password'])
            ->first();
        if(isset($user)){
            //$request->session()->regenerate();
            auth()->login($user);
            return redirect('/');
        }
        /*if(auth()->attempt($formData)){
            return "success!";
        }*/
        if(auth()->attempt($formData)){
            $request->session()->regenerate();
            return redirect('/');
        }
        return back()->withErrors([
            'password' => 'Wrong userdata!'
        ]);
    }

    public function logout(Request $request)
    {
        // logout user
        auth()->logout();

        // additional security
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function foreignProfile(User $user)
        // display a requested profile to a user
    {
        return view('users.profile', [
            'user' => $user
        ]);
    }

    public function selfProfile()
    {
        return view('users.selfProfile');
    }

    public function uploadForm()
        // show form for uploading a video
    {
        return view('users.upload');
    }

    public function store(Request $request){
        // method stores new user's video to database

        // first check if user sent valid data

        $genres = $request->genres;
        $genresString = "";
        foreach($genres as $genre){
            $genresString .= $genre . ",";
        }
        $genresString = trim($genresString, ",");
        $genresString.="";
        $formData = $request->validate([
            'title' => 'required',
            'videoFile' => ['required', 'max:400000'],   // video mustn't exceed 300 MB
            'videoImage' => ['required'],
            'description' => 'nullable',
            'genre' => 'nullable'
        ]);

        // user who uploaded the video
        //$user = User::find(1);
        $user = auth()->user();

        /*$formData = [
            'genre' => 'entertainment',
            'title' => $request->title,
            'author' => $user->name,
            'description' => $request->description,
            'views' => 0,
            'path' => $video->store('videos'),
            'user_id' => 3
        ];*/

        // put video into a variable $video
        $video = $request->file('videoFile');

        // store video and put its path into 'path'
        $formData['path'] = $video->store('videos', 'public');

        // store image that represents the video
        $videoImage = $request->file('videoImage');
        $formData['videoImagePath'] = $videoImage->store('videoImages', 'public');

        // create other video metadata
        $formData['user_id'] = $user->id;
        $formData['author'] = $user->name;
        //$formData['genre'] = 'music';
        $formData['genre'] = $genresString;
        $formData['views'] = 0;


        // insert video metadata into DB
        Video::create($formData);
        return redirect('/');
        /*Video::create([
            'title' => 'FirstVideo2',
            'author' => User::find(1)->name,
            'description' => 'Fun video for watching',
            'views' => 0,
            'path' => '/fake2.mp4',
            'user_id' => 1,
            'genre' => 'fun'
        ]);*/
    }
}
