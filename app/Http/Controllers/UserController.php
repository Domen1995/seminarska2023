<?php

namespace App\Http\Controllers;

use App\Mail\SignUp;
use App\Models\StudentStatistics;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Models\TeacherSettings;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\RateLimiter;

class UserController extends Controller
{

    public function mainPage(Request $request)
    {
        $user = auth()->user();
        if($user==null){
            return $this->loginForm();
        }
        return $this->redirectToMainpage($request, $user);
    }

    public function registrationForm($actor)
        // show a form for creating an account
    {
        //return view('users.registrationForm');
        return view(''.$actor.'.registrationForm');
    }


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
        Mail::send('mails.signup', ['name' => $name, 'verificationCode' => $formData['verificationCode'], 'email' => $request->email], function ($message){
            $message->from('streamingservice@gmail.com');
            $message->to($GLOBALS['email'])->subject('Registration');

        });
        if($actor == "teacher"){  // teacher
            $formData['isTeacher'] = "1";
        }elseif($actor == "student"){  // student
            $formData['isTeacher'] = "0";
        }
        $user = User::create($formData);


        return redirect('/')->with('message', $user->name.', please check your email to sign in.');
        /*auth()->login($user);
        return redirect('/')->with('message', 'Welcome', auth()->user()->name);*/
    }

    public function verifyMail(Request $request)
    {
        // check if the user is in database and if the verification code is correct
        /*$user = User::where('name', $request->n)->first();
        if($user==null || $user->verificationCode != $request->c) return "Wrong verification data!";//redirect('/users/registrationForm')->with('message', 'Wrong verification data!');
        */
        /*$u = Faculty::find(1);
        dd($u);*/
        $user = User::where('name', $request->n)->first();
        //$isStudent = true;
        if($user==null || $user->verificationCode != $request->c) return "Wrong verification data!";
        /*{
            // if user isn't among students, let's see if he's among techers:
            $user = Teacher::where('name', $request->n)->first();
            if($user==null || $user->verificationCode != $request->c) return "Wrong verificaton data!";
            $isStudent = false;
        }*/
        // if mail has already been verified, inform user
        if($user->verified==1) return "The email has already been verified!";
        $user->verified = 1;
        $user->save();

        // create a record in TeacherSettings if teacher or in UserStatistics if user
        if($user->isTeacher){
            TeacherSettings::create([
                'user_id' => $user->id,
            ]);
        }else{
            StudentStatistics::create([
                'user_id' => $user->id
            ]);
        }
        auth()->login($user);
        // assign the loggedin user statut of student or teacher for later use in this session
        if($user->isTeacher){
            return redirect('/teachers/mainpage')->with('message', 'Welcome to the community, '. $request->n.'!');
        }else{
            return redirect('/students/mainpage')->with('message', 'Welcome to the community, '. $request->n.'!');
        }
        //return redirect('/')->with('message', 'Welcome to the community, '. $request->n.'!');//auth()->user()->name.'!');
    }

    public function deleteBeforeVerified(Request $request)
    {
        $user = User::where('verificationCode', $request->c)->first();
        if($user->verified == 0){
            $user->delete();
            return redirect('/')->with('message', 'Successfully deleted, you can register again');
        }
        return "Can't be deleted, the email was already verified";
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

        // limit login attempts from same IP address
        $hasMoreAttempts = RateLimiter::attempt($request->ip(), $perMinute = 5, function(){});
        if(!$hasMoreAttempts) return back()->withErrors(['password' => 'Too many attempts, you can retry in 1 minute']);
        //if(RateLimiter::tooManyAttempts($request->ip(), $perMinute = 1)) return "You can try logging again in 1 minute";

        $formData = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $authData = [
            'password' => $request->password
        ];

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

        // if user logged in with his email, put email for incoming authentication, otherwise nickname
        if(filter_var($request->email, FILTER_VALIDATE_EMAIL)) $authData['email'] = $request->email;//$formData['email'] = $request->email;
        else $authData['name'] = $request->email;//$formData['name'] = $request->email;

        // if user exists, but hasn't verified throught email, don't log him in

        $user = User::where('name', $request['email'])
                ->orWhere('email', $request['email'])->first();
        //$userAndType = $this->getUserAndType($request);
        if($user!=null && ($user->verified==0)){
            return back()->withErrors([
                'password' => 'You need to click on the link you received on email.'
            ]);
        }
        // if user is student, don't let him in if his IP is invalid
        if(!$user->isTeacher){
            if(!StudentStatistics::ipLoginValidation($request, $user)){
                return back()->with('message', 'Wrong IP address!');
            }
        }

        if(auth()->attempt($authData)){//$formData)){   // login user
            $request->session()->regenerate();  // security
            return $this->redirectToMainpage($request, $user);
            /*if($user->isTeacher){
                return redirect('/teachers/mainpage')->with('message', 'Welcome back, '.$user->name);
            }else{
                return redirect('/students/mainpage')->with('message', 'Welcome back, '.$user->name);
            }*/
            //return redirect('/')->with('message', 'Welcome back, '.auth()->user()->name);
        }

        return back()->withErrors([
            'password' => 'Wrong userdata!'
        ]);
    }

    /*
    public function getUserAndType($request){
        // return par user and his type(teacher, student)
        $user = Student::where('email', $request->email)->first();
        $userType = "student";
        if($user==null){
            $user = Teacher::where('email', $request->email)
                            ->orWhere('name', $request->email)->first();
            $userType = "teacher";
        }
        return ["user" => $user, "type" => $userType];
    }*/

    public function logout(Request $request)
    {
        // logout user
        auth()->logout();

        // security
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function redirectToMainpage(Request $request, $user)
    {
        //$user = auth()->user();
        if($user->isTeacher){
            return redirect('/teachers/mainpage')->with('message', 'Welcome back, '.$user->name);
        }else{
            // get first of possible 2 student's IP addresses
            $studentIP = StudentStatistics::where('user_id', $user->id)->first('ip_addresses')->ip_addresses;
            // if it isn't set, redirect student to the page where he will confirm or reject current IP to be permanent
            if($studentIP == null) return redirect('/students/ipForm');
            // let student in only if his IP in DB matches with the one he's currently using
            //$ipMatches = str_contains($studentIP, sha1($request->ip()));
            //if($ipMatches) return redirect('/students/mainpage')->with('message', 'Welcome back, '.$user->name);
            // if they don't match:
            return redirect('/students/mainpage')->with('message', 'Welcome back, '.$user->name);
        }
    }

    /*
    public function foreignProfile(User $user)
        // display a requested profile to a user
    {
        return view('users.profile', [
            'user' => $user,
            'videos' => Video::where('user_id', $user->id)->paginate(4)
        ]);
    }
*/
    //public function selfProfile();
    /*
    {
        $user = auth()->user();
        //dd($user->id);
        /*$videos = Video::where('user_id', $user->id)->paginate(4);//get();
        foreach($videos as $video) echo $video->title;
        dd($videos);*/
        //$u = User::find(auth()->user()->id);
        /*return view('users.selfProfile', [
            'user' => $user,//User::find(auth()->user()->id),
            'videos' => Video::where('user_id', $user->id)->paginate(4)//Video::where('user_id', $user->id)->get()//User::find(auth()->user()->id)->video()->get()
            /*'channelDescription' => User::find(auth()->user()->id)->description*/
        /*]);
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
        /*$genres = $request->genres;
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
        /*$video = $request->file('videoFile');
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
    /*}

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
    }*/
}
