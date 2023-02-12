<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;


class TeacherController extends Controller
{

    /*
    public function register(Request $request, $actor)
    {
        $formData = $request->validate([
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'name' => ['required', Rule::unique('users', 'name'), 'min:5', 'max:20'],   // nickname
            'password' => ['required', 'min:7']
        ]);

        $hasMoreAttempts = RateLimiter::attempt($request->ip(), $perMinute=2, function(){});
        if(!$hasMoreAttempts) return back()->withErrors(['password' => "Too many attempts, try again in 1 minute"]);

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
        if($actor == "t"){
            Teacher::create($formData)
        }
        $user = User::create($formData);


        return redirect('/')->with('message', 'Please check your email to sign in.');
        /*auth()->login($user);
        return redirect('/')->with('message', 'Welcome', auth()->user()->name);*/
    //}

    /*
    public function verifyMail(Request $request)
    {
        // check if the user is in database and if the verification code is correct
        $user = User::where('name', $request->n)->first();
        if($user==null || $user->verificationCode != $request->c) return redirect('/users/registrationForm')->with('message', 'Wrong verification data!');

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
        return view('teachers.loginForm');
    }

    /*public function login(Request $request)
    // log teacher in
    {
        /*
        $user = User::find(2);
        auth()->login($user);
        return redirect('/');*/

        // limit login attempts from same IP address
        /*$hasMoreAttempts = RateLimiter::attempt($request->ip(), $perMinute = 5, function(){});
        if(!$hasMoreAttempts) return back()->withErrors(['password' => 'Too many attempts, you can retry in 1 minute']);
        //if(RateLimiter::tooManyAttempts($request->ip(), $perMinute = 1)) return "You can try logging again in 1 minute";

        $formData = $request->validate([
            //'email' => ['required'/*, 'email'*/ //],
        /*    'password' => 'required'
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
        }

        // if user logged in with his email, put email for incoming authentication, otherwise nickname
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
    }*/

    public function mainpage()
    {
        return "teacher";
    }

    public function selfProfile()
    {
        $user = auth()->user();
        //dd($user->id);
        /*$videos = Video::where('user_id', $user->id)->paginate(4);//get();
        foreach($videos as $video) echo $video->title;
        dd($videos);*/
        //$u = User::find(auth()->user()->id);
        return view('users.selfProfile', [
            'user' => $user,//User::find(auth()->user()->id),
            'videos' => Video::where('user_id', $user->id)->paginate(4)//Video::where('user_id', $user->id)->get()//User::find(auth()->user()->id)->video()->get()
            /*'channelDescription' => User::find(auth()->user()->id)->description*/
        ]);

    }

    public function updateProfile(Request $request)
        // update user's profile (channel description, ...)
    {
        $user = User::find(auth()->user()->id);
        $user->description = $request->channelDescription;
        $user->save();
        return redirect('/')->with('message', 'Your data has been successfully updated!');
    }

    public function uploadForm()
    // show form for uploading a video
    {
        return view('users.upload');
    }

    public function store(Request $request){
        // method stores new user's video to database

        // first check if user sent valid data
        //$bodyContent = $request->getContent();
        //$body = $request->all();
        //dd($body->file('videoFile'));
        //dd($request->file('videoFile'));  // ta deluje
        /*dd($_FILES['videoFile']);
        dd($_FILES[$body['videoFile']]);
        dd(($body['videoFile']));
        dd($body['title']);
        return $request->videoData->title;*/
        $genres = $request->genres;
        $genresString = "";
        if($genres!=null){
            foreach($genres as $genre){
                $genresString .= $genre . ",";
            }
            $genresString = trim($genresString, ",");
            $genresString.="";
        }
        $formData = $request->validate([
            'title' => 'required',
            // video mustn't exceed 400 MB
            'videoFile' => ['required', 'max:400000', 'mimetypes:video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi'],
            'videoImage' => ['required', 'mimes:jpeg,png,jpg,svg'],
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

    public function deleteVideo(Request $request)
        // delete video on user's initiative
    {
        $video = Video::find($request->vidId);
        $author = User::where('id', $video->user_id)->first();
        // check if the video really belongs to this user
        if(auth()->user()!= $author) abort(403, "Don't try to delete other's videos");

        //echo(filesize('storage/'.$video->path));
        // delete video from storage
        if(file_exists('storage/'.$video->path)) unlink('storage/'.$video->path);
        //Storage::delete('SSiAGN7VEtNeYFwcFi5JHY81mdTRnTFMFMCjjSLw.jpg');
        //Storage::disk('D')
        //dd(filesize('storage/'.$video->path));

        // delete image that represents the video from storage
        if(file_exists('storage/'.$video->videoImagePath)) unlink('storage/'.$video->videoImagePath);
        //echo($video->videoImagePath);
        //Storage::delete('storage/'.$video->videoImagePath);
        //dd($video->videoImagePath);

        // delete record about video from DB
        $video->delete();
        return redirect('/')->with("message", "You've successfully deleted your video");
    }

    /*public function test()
    {
        $fac = new User;
        $class = get_class($fac);
        if(!str_contains($class, "Facult")){
            echo "yes";
        }else{
            echo "no";
        }
    }*/

    public function test2()
    {

    }

    public function test()
    {

    }
}
