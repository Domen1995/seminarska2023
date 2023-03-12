<?php

namespace App\Http\Controllers;

use App\Console\Commands\WebSocketServer;
use App\Models\User;
use App\Models\Video;
use App\Models\Course;
use App\Models\CoursesUser;
use App\Models\Ip_testing;
use Illuminate\Http\Request;
use App\Models\TeacherSettings;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

use function PHPUnit\Framework\returnSelf;

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
        $user = auth()->user();
        return view('teachers.mainpage', [
            'user' => $user,
            'courses' => Course::where('user_id', $user->id)->paginate(8)
        ]);
    }

    public function newCourseForm()
    {
        return view('teachers.newCourseForm');
    }

    public function selfProfile()
    {
        $user = auth()->user();
        //dd($user->id);
        /*$videos = Video::where('user_id', $user->id)->paginate(4);//get();
        foreach($videos as $video) echo $video->title;
        dd($videos);*/
        //$u = User::find(auth()->user()->id);
        $info_for_students = TeacherSettings::where('user_id', $user->id)->first()->info_for_students;
        return view('teachers.selfProfile', [
            'user' => $user,//User::find(auth()->user()->id),
            'info_for_students' => $info_for_students
            //'videos' => Video::where('user_id', $user->id)->paginate(4)//Video::where('user_id', $user->id)->get()//User::find(auth()->user()->id)->video()->get()
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

    public function uploadForm(Course $course)
    // show form for uploading a video
    {
        return view('teachers.upload', [
            'course' => $course
        ]);
    }

    public function store(Request $request, Course $course){
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
        }*/
        if($course->user_id != auth()->user()->id){
            return back()->with('message', 'Only the course owner can upload videos');
        }
        $formData = $request->validate([
            'title' => 'required',
            // video mustn't exceed 400 MB
            'videoFile' => ['required', 'max:400000', 'mimetypes:video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi'],
            /*'videoImage' => ['required', 'mimes:jpeg,png,jpg,svg'],
            'description' => 'nullable',
            'genre' => 'nullable'*/
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
        /*$videoImage = $request->file('videoImage');
        $formData['videoImagePath'] = $videoImage->store('videoImages', 'public');*/

        // create other video metadata
        //$formData['user_id'] = $user->id;
        $formData['author'] = $user->name;
        $formData['user_id'] = $user->id;
        //$formData['genre'] = 'music';
        //$formData['genre'] = $genresString;
        //$formData['views'] = 0;   // views by a student

        $formData['course_id'] = $course->id;
        $formData['duration'] = 30;

        // insert video metadata into DB
        Video::create($formData);
        //return redirect('/videos/courseVideos/'.$course->id);
        return redirect('/teachers/coursePage/'.$course->id);
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
        //if(file_exists('storage/'.$video->videoImagePath)) unlink('storage/'.$video->videoImagePath);

        //echo($video->videoImagePath);
        //Storage::delete('storage/'.$video->videoImagePath);
        //dd($video->videoImagePath);

        // delete record about video from DB
        $video->delete();
        //return redirect('/')->with("message", "You've successfully deleted your video");
        return back()->with('message', "You've successfully deleted your video");
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

    public function studentPermissions()
        // shows the view in which teacher can edit permissions given to his students
    {
        $teacher = auth()->user();
        if(!$teacher->isTeacher) abort(403, "You're not even a teacher");
        //$anyTeacherscourse = Course::where('user_id', $teacher->id)->first();
        //$courses = Course::where('user_id', $teacher->id)->get();
        $allowedEmailsString = TeacherSettings::where('user_id', $teacher->id)->first()->allowedEmails;
        // allowed emails are stored as comma separated values, put them into array:
        /*if($anyTeacherscourse==null){
            return back()->with('message', "Let's first create a course before limiting students");
        };
        return $anyTeacherscourse->allowedEmails;*/
        //return $allowedEmailsString;
        //$allowedEmails = explode(",", $anyTeacherscourse->allowedEmails);
        if($allowedEmailsString == null) $allowedEmails = [];
        else $allowedEmails = explode(',', $allowedEmailsString);
        // because last value after comma is empty, we need to delete it from array:
        unset($allowedEmails[count($allowedEmails)-1]);
        return view('teachers.studentPermissions', [
            'teacher' => $teacher,
            'allowedEmails' => $allowedEmails
        ]);
    }

    public function addAllowedEmail(Request $request)
        // to each course that is owned by the teacher, add the ending of email addresses to make courses visible to those students
    {
        $emailEnding = $request[0];
        // don't allow email ending containing a comma
        if(str_contains($emailEnding, ",")) return;
        $teacher = auth()->user();
        if(!$teacher->isTeacher) abort(403, "You're not even a teacher");
        $teacherSettings = TeacherSettings::where('user_id', $teacher->id)->first();
        $allowedEmailsTillNow = $teacherSettings->allowedEmails;
        $allowedEmailsFromNow = $allowedEmailsTillNow /*. ',' */. $emailEnding.',';
        $teacherSettings->update([
            'allowedEmails' => $allowedEmailsFromNow
        ]);

        $courses = Course::where('user_id', $teacher->id)->get();
        foreach($courses as $course){
            //$allowedEmailsTillNow = $course->allowedEmails;
            $course->update([
                'allowedEmails' => $allowedEmailsFromNow//$allowedEmailsTillNow .','. $emailEnding
            ]);
        }
        return "added";
    }

    public function removeAllowedEmail(Request $request)
    {
        $emailEnding = $request[0];
        $teacher = auth()->user();
        if(!$teacher->isTeacher) abort(403, "You're not even a teacher");
        $teacherSettings = TeacherSettings::where('user_id', $teacher->id)->first();
        $allowedEmailsTillNow = $teacherSettings->allowedEmails;
        $allowedEmailsFromNow = str_replace($emailEnding.",", "", $allowedEmailsTillNow);
        $teacherSettings->update([
            'allowedEmails' => $allowedEmailsFromNow
        ]);

        $courses = Course::where('user_id', $teacher->id)->get();
        foreach($courses as $course){
            //$allowedEmailsTillNow = $course->allowedEmails;
            $course->update([
                'allowedEmails' => $allowedEmailsFromNow//$allowedEmailsTillNow .','. $emailEnding
            ]);
        }
        return "removed";
    }

    public function enrollStudent(Request $request)
    {
        $enrollment = CoursesUser::where('user_id', $request->studentID)
                                    ->where('course_id', $request->courseID)
                                    ->first();
        if($enrollment==null) return back()->with('message', 'Enrollment failed');
        if($enrollment->status == "requested"){
            $enrollment->status = "enrolled";
            $enrollment->save();
            return back()->with('message', 'Student successfully enrolled');
        }
        return back()->with('message', 'Enrollment failed');
    }

    public function coursePage(Course $course)
        // show all videos of the selected course to the author and also course manager if user is the owner
    {
        $user = auth()->user();
        if(!($user->isTeacher && $user->id == $course->user_id)) return abort(403, "You're not even the owner of this course");
        $videos = Video::where('course_id', $course->id)->paginate(24);
        //$user = auth()->user();
        // get all students that requested for enrollments in this course, if it's owned by this user and if their status is "requested"
        //if($user->isTeacher && $user->id == $course->user_id){
        $studentsToEnroll = User::whereIn('id', CoursesUser::where('course_id', $course->id)
                                                            ->where('status', 'requested')->get('user_id'))
                                        ->get();
        return view('videos.courseVideos', [
            'videos' => $videos,
            'course' => $course,
            'user' => auth()->user(),
            'studentsToEnroll' => $studentsToEnroll
        ]);
    }

    // morda se profesorji ne bi v clusterje, da ne bi prihajalo do dvojnega čekiranja. Saj ne bi kaj dosti prihranili na času,
    // če bi vsak čekiral zase. in bi dodeljeval

    //in bi se potem pri coursesUser timestamp ... sploh ne rabi timestampa čekiranja ne študent ne učitelj, če ni clusterjev
    // profesorjev. in učitelj ni omejen da ne more čekirat 2,5 h. Študent ne more še 1krat noter, ker je njegov zapis v ip_check
    // dokler profesor ne zapre čekiranja. Če čekirata 2 predmeta naenkrat, na katera je vpisan, bi lahko imel timestamp da se mu
    // onemogoči za 2h čekirati, ampak to pomeni, da se mu predmeta prekirivata in vseeno loh gleda posnetlke obeh; itak mu 0
    //globalno ne poveča. Profesorja bi edino lahko zmedlo, da se mu je pojavi nekdo, ki ga ni v razredu. Potem bi se lahko za
    // drugega počekiral študent, tako da ni dovoljeno, da se pojavi na obeh. Če 2 hkrati čekirata, si mora izbrati enega in
    // Študent mora klikniti, da potrjuje, da je v razredu? To bi vzelo čas. Ampak če se samo prijavi noter ali ga prijavi sošolec,
    //... Neka potrditev bi morala biti s strani študenta - potem ko je že pravi IP -, da se pojavi na ekranu profesorja. Če ne,
    // bi med drugim goljufali. Če se pojavi na enkranu ime, ki ni v razredu, ga profesor loh bana. Mora bit napis: "Ste v tem hipu
    // na predavanju coursa Prog 1?" Prihranilo bi klik, če ne. 0 ni treba potrditi; itak ne more študent gledati na IP-ju šole,
    // sicer bi si lahko delili. Tudi v štud domu si loh delijo... Samo na 1 napravi je loh prijavljen študent hkrati. Dobi minus
    // točko, če se zgodi, da je na 2 napravah hkrati aktiven, če večkrat, pa ban. Prek sessiona se to ugotovi. Ne rabi imeti sta-
    // lnega IP-ja študent, ampak se samo ne smejo zgoditi hkratne prijave - sessioni - oz. aktivnosti na več napravah. Študent
    // ni omejen na IP. Ni treba 0 potrditi, da si na predavanju; če si na aplikaciji na faksu med predavanjem in profesor testira
    // in te ni na predavanju, to pomeni, da ne boš hodil na ta predavanja in te profesor izbaci. Če se ti prekriva, ti loh
    // profesor z 1 klikom da neomejen dostop; če bi se ti pojavilo vprašanje "ste na predavanju" in ne bi odobril, bi tako ali
    // tako dobil ološ in za brezveze zadrževati, da mora cela skupina to odobriti. Profesor naj bana nekoga, ki se pojavi, a ga
    // ni na predavanju; loh da goljufa in se je nekdo prijavil namesto njega. Študent ima timestamp in se nikjer ne more potem...
    // Potem se ne bi mogel tudi na istem predmetu počekirati, če bi prof. ponovil. Ima timestamp 1,5h v CoursesUser in se loh še
    // 1x čekira samo v tem recordu, če ni več v ip_checkingu recorda. Kaj, če tolikokrat bil prisoten in tekom tekočega predavanja
    // nekje drugje na šoli lihkar spremlja posnetek drugega predavanja? Mora odobriti oz. zavrniti prisotnost -
    // polje se pojavi avtomatsko vsem pri kakršnikoli akciji na spl. strani -; v obeh primerih
    // timestamp in se ga 1,5h ne vpraša več. Ne timestamp, ampak se v ip_testings pod shrani kot nekdo, ki se mu bo naredil ološ.
    // Vsak je takoj lahko testiran na nek drug predmet in prof. loh takoj testira, (ni timestampov). Je timestamp
    // študenta za pol ure - izključujoč za dotični predmet, če mora npr. resetir testiranje -
    // , ker potem je že možno bit testiran na 2. predavanju. Če hkrati več testiranj, si izbere, katerega. Zapiše se tudi pri zad-
    // njem timestampu testiranja, za kateri course je bil, tako da se loh takoj spet testira, če prof. resetira testiranje in se
    // s tem izbriše record iz ip_testing

    public function checkIp(Course $course, Request $request)
        // send to teacher a page which will connect him to websocket and he'll start waiting for
        // students to join
    {
        // store in DB that the course is in the presence checking process
        $course = Course::find($course->id);
        /*$seconds_since_last_checking = time() - $course->last_time_ip_check;
        if($seconds_since_last_checking <= 7200) return back()
            ->with('message', $course->name.' can be checked for presence again in '.$seconds_since_last_checking.' seconds.');*/
        $course->isCurrentlyChecking = 1;
        $course->ipForChecking = $request->ip();
        //$course->last_time_ip_check = time();
        $course->save();
        $webSocketToken = md5(uniqid());
        $teacher_id = auth()->user()->id;
        $existingTestings = Ip_testing::where('course_id', $course->id)->delete();
        //if($existingTestings!=null) $existingTestings->destroy();
        // if already exists teacher's record in DB ip_checkings, first delete it
        /*$existingTesting = Ip_testing::where('user_id', $teacher_id)->first();
        if($existingTesting!=null) $existingTesting->delete();*/
        Ip_testing::create([
            'user_id' => $teacher_id,
            'course_id' => $course->id,
            //'ip' => $request->ip(),
            'is_tester' => 1,
            'token' => $webSocketToken
        ]);
        $enrolledStudents = User::whereIn('id', CoursesUser::select('user_id')->where('course_id', $course->id)->get())->get();
        return view('teachers.ipChecking', [
            'token' => $webSocketToken,
            'course' => $course,
            'enrolledStudents' => $enrolledStudents
        ]);
    }

    public function submitPresentStudents(Course $course, Request $request)
    {
        // check if the caller of function really is teacher who owns the course
        if($course->user_id!=auth()->user()->id) abort(403, "You don't even own this course");

        Ip_testing::clear_DB_ip_data($course);
        $present_student_ids = $request->studentIds;
        /*foreach($studentIds as $studentId){
            echo "Submited was:". $studentId;
        }*/
        /*
        $coursesUser_all_present = CoursesUser::whereIn('user_id', $studentIds)
                                            ->where('course_id', $course->id)
                                            ->get();
        foreach($coursesUser_all_present as $courseUser_one){
            $courseUser_one->presences = $courseUser_one->presences+1;
            $courseUser_one->save();
        }*/
        //dd($present_student_ids);
        // all records in courses_users table that belong to this course
        $all_of_this_course = CoursesUser::where('course_id', $course->id)->get();
        // if student was present increase presences, else increase screwUps. Also store student ids of the course for later
        $students_in_course_ids = [];
        foreach($all_of_this_course as $student_of_course){
            array_push($students_in_course_ids, $student_of_course->user_id);
            //dd($student_of_course, $present_student_ids);
            if($present_student_ids!=null && in_array($student_of_course->user_id, $present_student_ids)){
                $student_of_course->presences = $student_of_course->presences+1;
            }else{
                $student_of_course->screwUps = $student_of_course->screwUps+1;
            }
            $student_of_course->save();
        }
        $students_in_course = User::whereIn('id', $students_in_course_ids)->get();
        //dd(implode(',', $present_student_ids));
        return view('teachers.after_ipChecking', [
            'presentStudentIds' => $present_student_ids,
            'studentsInCourse' => $students_in_course,  // all students enrolled in course
            'course_id' => $course->id
        ]);
    }

    public function revertIpChecking(Request $request)
        // decrement presences to the students who were presen and decrement screwups to the ones who werent
    {
        $course_id = $request->course;
        $present_student_ids = explode(",", $request->presentIds);
        $present_students = CoursesUser::where('course_id', $course_id)
                                ->whereIn('user_id', $present_student_ids)
                                ->get();

        CoursesUser::reduce_presences($present_students, 1);

        $absent_students = CoursesUser::where('course_id', $course_id)
                                ->whereNotIn('user_id', $present_student_ids)
                                ->get();
        CoursesUser::reduce_screwUps($absent_students, 1);
        return redirect('/teachers/coursePage/'.$course_id)->with('message', 'As the last presence checking has never been performed');
    }

    public function updateStudentsInfo(Request $request)
        // updates field under "some informations for your student, if needed"
    {
        TeacherSettings::where('user_id', auth()->user()->id)->update(["info_for_students" => $request[0]]);
    }

    public function terminate_testing(Course $course)
        // set value to 0 at isCurrentlyChecking in the table and clear Ip_testing table for the course
    {
        //$course = Course::where('id', $course->id)->first();
        if($course!=null){
            if(auth()->user()->id != $course->user_id) return abort(403, "Don't try to sabotage testings");
            $course->isCurrentlyChecking = 0;
            $course->save();
            Ip_testing::where('course_id', $course->id)->delete();
        }
        return redirect('/')->with('message', 'The testing was successfully stopped');
    }

    public function manage_students(Course $course)
        // list students, enrolled in the course
    {
        return view('teachers.students_list', [
            'students' => User::whereIn('id', CoursesUser::where('course_id', $course->id)->pluck("user_id")->toArray())->get()
        ]);
    }

    public function test()
    {
        //$users = User::where('id', '>', 0)->get()->map->only('name');
        $users = User::where('id', '>', 0)->pluck("name")->toArray();
        dd($users[2]);
        //$users = User::where('id', '>', 0)->pluck('id')->toArray();
        //dd(in_array(5, $users));
        //return view('test');
        //return $_SERVER['SERVER_ADDR'];
        //TeacherSettings::create(["user_id" => 8]);
        //Cache::flush('client');
        //$clients = WebSocketController::$clients;
        /*foreach(WebSocketController::$clients as $client){
            $response = ["type" => "fromTeacherClass"];
            $client->send(json_encode($response));
        }*/
        //dd(WebSocketController::$clients->count());
    }

    public function test2()
    {

    }
}
