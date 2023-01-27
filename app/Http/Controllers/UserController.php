<?php

namespace App\Http\Controllers;

use App\Mail\SignUp;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
            'name' => ['required', Rule::unique('users', 'name'), 'min:5', 'max:20'],   // nickname
            'password' => ['required', 'min:7']
        ]);
        $formData['password'] = bcrypt($formData['password']);  // hash password

        $formData['verificationCode'] = sha1(time());

        //$success = User::sendMail($request->email);
        $name = $request->name;
        $GLOBALS['email'] = $request->email;
        //Mail::to($request->email)->send(new SignUp($name));
        Mail::send('mails.signup', ['name' => $name, 'verificationCode' => $formData['verificationCode']], function ($message){
            $message->from('streamingservice@gmail.com');
            $message->to($GLOBALS['email'])->subject('Registration');

        });
        $user = User::create($formData);


        return redirect('/')->with('message', 'Please check your email to sign in.');
        /*auth()->login($user);
        return redirect('/')->with('message', 'Welcome', auth()->user()->name);*/
    }

    public function verifyMail(Request $request)
    {
        // check if the user is in database and if the verification code is correct
        $user = User::where('name', $request->n)->first();
        if($user==null || $user->verificationCode != $request->c) return redirect('/users/registrationForm')->with('message', 'Something went wrong, please register again');

        // if mail has already been verified, inform user
        if($user->verified==1) return "The email has already been verified!";
        $user->verified = 1;
        $user->save();
        auth()->login($user);
        return redirect('/')->with('message', 'Welcome to the community, '. auth()->user()->name.'!');
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
            //'email' => ['required'/*, 'email'*/],
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

        // if user logged in with his email, authenticate with email, otherwise nickname
        if(filter_var($request->email, FILTER_VALIDATE_EMAIL)) $formData['email'] = $request->email;
        else $formData['name'] = $request->email;

        // if user exists, but hasn't verified throught email, don't log him in
        $user = User::where('name', $request['email'])
                ->orWhere('email', $request['email'])->first();
        if($user!=null && ($user->verified==0)){
            return back()->withErrors([
                'password' => 'You need to click the link you received on email.'
            ]);
        }

        if(auth()->attempt($formData)){   // login user
            $request->session()->regenerate();  // security
            return redirect('/')->with('message', 'Welcome back, '.auth()->user()->name);
        }

        return back()->withErrors([
            'password' => 'Wrong userdata!'
        ]);
    }

    public function logout(Request $request)
    {
        // logout user
        auth()->logout();

        // security
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
        return view('users.selfProfile', [
            'user' => User::find(auth()->user()->id)
            /*'channelDescription' => User::find(auth()->user()->id)->description*/
        ]);
    }

    public function updateProfile(Request $request)
        // update user's profile (channel description, ...)
    {
        $user = User::find(auth()->user()->id);
        if(strlen($request->channelDescription)>2){
            $user->description = $request->channelDescription;

        }
        $user->save();
        return redirect('')->with('message', 'Your data has been successfully updated!');
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
