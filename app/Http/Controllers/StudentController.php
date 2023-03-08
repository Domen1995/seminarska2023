<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use App\Models\Course;
use App\Models\Ip_testing;
use Illuminate\Http\Request;
//use Illuminate\Support\Carbon;
use App\Models\StudentSettings;
use App\Models\CoursesUser;//CourseStudent;

class StudentController extends Controller
{
    public function mainpage(Request $request)
    {
        $student = auth()->user();
        // check if teacher is currently checking course student's enrolled in; if doesn't proceed normally
        $coursesChecking = Course::courses_of_student_checking_ip($student);
        if(count($coursesChecking)>=1){
            //$last_time_present = StudentSettings::where('user_id', $student->id)->first()->last_time_present;
            // if at least 2 hours passed since last IP checking, allow it again
            //if((time() - $last_time_present)>7200){
            //$ipCheckingPage = $this->ipChecking($coursesChecking, $request);
            $courses_available_to_student_check = Ip_testing::courses_available_to_student_ip_check($student, $coursesChecking, $request);
            //dd($request->ip());
            if(count($courses_available_to_student_check)>0){
                return view('students.ipChecking_course_selection', [
                    'courses' => $courses_available_to_student_check
                ]);
            }
            //if($ipCheckingPage!="access_denied") return $ipChecking_course_selection;
            //}
        }// return $this->ipChecking($coursesChecking, $request);
        // if student searched in search bar for matching courses, the request has limitations:
        /*if($request->has('limitations')){
            $courses = Course::findMatching($request->limitations);
        // if this was a usual arrival on the page, we retrieve all courses the student is signed in
        }else{*/

        $studentsEmailEnding = User::emailEnding($student->email);
        $courses = Course::whereIn('id', CoursesUser::select('course_id')->where('user_id', auth()->user()->id)->get())  //courses student's enrolled in or waiting for approval
                    ->orwhere('allowedEmails', 'like', '%'.$studentsEmailEnding.'%')  //courses matching student's email address
                    ->paginate(8);

        // for all courses the student requested enrollment or is enrolled:
        $coursesUsers = CoursesUser::where('user_id', $student->id)->get();
        if (count($coursesUsers)==0) $coursesUsers = null;
        else $coursesUsers = CoursesUser::associateCourseId($coursesUsers);
        //$availableCourses = Course::where('allowedEmails', 'like', '%'.$student->email.'%');

        return view('students.mainpage', [
            // select all courses that student is signed in
            'courses' => $courses,
            'user' => $student,
            'coursesUsers' => $coursesUsers  // associated course_id points to each CoursesUser
            //'availableCourses' => $availableCourses
        ]);
    }

    public function requestEnrollment(Request $request, Course $course)
    {
        $student = auth()->user();
        // check if student's enrollment record already exists
        $enrollmentRecord = CoursesUser::where('course_id', $course->id)
                            ->where('user_id', $student->id)
                            ->first();
        if($enrollmentRecord!=null){
            return back()->with('message', 'You have already requested for this course');
        }

        CoursesUser::create([
            'user_id' => $student->id,
            'course_id' => $course->id
        ]);

        return back()->with('message', 'Teacher is noted to let you in');
    }

    public function deleteEnrollmentRequest(Request $request, Course $course)
        // deletes request to join a course from teacher's list for the student who changed his mind
    {
        $student = auth()->user();
        $enrollmentRecord = CoursesUser::where('course_id', $course->id)
                                        ->where('user_id', $student->id)
                                        ->first();
        // if the request doesn't exist, return
        if($enrollmentRecord==null){
            return back()->with('message', "Deletion unsuccessful");
        }
        // if teacher hasn't approved the request yet, request can be deleted
        if($enrollmentRecord->status == 'requested'){
            $enrollmentRecord->delete();
            return back()->with('message', "Your request was successfully removed from teacher's list");
        }
        // if teacher already admitted the student to his course, it's too late
        if($enrollmentRecord->status == 'approved') return back()->with('message', 'Too late, teacher has already let you in.');
        // any other case, just return with negative message
        return back()->with('message', "Deletion unsuccessful");
    }

    public function statistics()
    {
        return view('students.statistics');
        // samo 1 IP naslov ima loh študent, loh pa specifično za svoje course mu doda profesor še 1-ga. Če mu ga in se pri-
        // javita 2 istočasno, ga bana. Da ne bo omejen; loh si doda sam še 1ga, pod pogojem, da študira v tujem kraju - to bo vidno
        // vsem profesorjem -
        // na 2 lokacijah, s svarilom, da če je prijavljen na obeh istočasno,
        // onemogočena uporaba pod tem mailom in obema IP-jema. Moral si bo narediti kljukico, če si bo hotel dodati še 1 IP naslov. Ne, pred
        // gledanjem videa se bo počekiralo ujemanje še enkrat.
    }

    public function settings()
    {
        return view('students.settings');
    }

    public function coursePage(Course $course)
    {
        $user = auth()->user();
        // check if user is really enrolled
        $enrollment = CoursesUser::where('course_id', $course->id)
                                    ->where('user_id', $user->id)
                                    ->where('status', 'enrolled')
                                    ->first();
        if($enrollment==null) return back('message', "You're not even enrolled to this course");
        $videos = Video::where('course_id', $course->id)->paginate(24);
        return view('students.coursePage', [
            'videos' => $videos,
            'course' => $course,
            'user' => $user
        ]);
    }

    /*public function showIpForm(Request $request)
    {
        return view('students.ipForm', [
            'studentsIP' => $request->ip()
        ]);
    }

    public function addIp(Request $request)
        // add new permanent IP student chose or append one IP already exists
    {
        $student = auth()->user();
        $studentSettings = StudentSettings::where('user_id', $student->id)->first();
        $studentSettings->ip_addresses .= sha1($request->ip).',';
        $studentSettings->save();
        return redirect('/students/mainpage')->with('message', "Success. IP address was immediately hashed; we're not interested in your location.");
    }*/

    public function serve_websocket_ip_page(Course $course, Request $request)
    {
        // first check again if ip matches, if student didn't already validate his presence, ...
        $student = auth()->user();
        $info_valid = Ip_testing::student_info_valid_for_checking($student, $course, $request);
        if(!$info_valid) return back()->with('message', "Your data isn't valid for ip testing");
        // update student's data about ip testing
        StudentSettings::where('user_id', $student->id)
                ->update(["last_time_present" => time(), "course_of_last_presence_id"=> $course->id]);
        // give student an unique token
        $webSocketToken = md5(uniqid());
        // create a record about student testing in DB
        Ip_testing::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'ip' => $request->ip(),
            'is_tester' => 0,
            'token' => $webSocketToken
        ]);
        // serve student page that will establish websocket connection for testing IP
        return view('students.ipChecking', [
        'student' => $student,
        'token' => $webSocketToken
        //'ip' => $request->ip()
]);
    }


    /*public function ipChecking($coursesChecking, Request $request)
        // NOT IN USE
    {
        // if only 1 course that student't enrolled in is checking IP at this moment, at him to IpTesting table.
        // če se IP ne ujema, ni obveščen, samo če se, je - redirectan je na pohvalni view. povezava loh traja samo 1 hip in je zabeleženo, zato
        // ni treba includati na vsak view posebej, ki ga obišče, nič.
        $student = auth()->user();
        // if the student already confirmed his presence, don't let him in again. Tabela ip_testings se za course cleara 2 h
        // po zaprtju in šele takrat loh drug profesor v clusterju odpre test. Tudi študent se do 2 h po zaprtju ne more nekje
        // Profesorji lahko tudi prekrivajoče med sabo dajo testirat, tudi če so v istem clusterju - ker imajo študente, ki so nekje
        // vpisani, nekje ne -. Samo študent se ne more 2,5 ure ponovno počekirati. Timestamp zadnjega čekiranja je shranjen pri študentu.
        // se pa hrani v tabeli ip_checking zapis študenta dokler profesor ne gre ven in za ta predmet se lahko spet počekira prej kot
        // po 2 h, če profesor restarta test - NE. Še po tem, ko profesor gre ven, se morda hrani zapis študenta, če profesor zafrkne test, da
        // loh gre študent spet noter samo za njegov predmet ... Raje profesor ne more testirati istega coursa 2,5 ure ponovno.
        // Zapis o študentu ip_checking se izbriše, ko profesor neha testirati. Kaj, če profesor izgubi povezavo? Da se mu ne bodo
        // ponovno prijavljali študenti noter? Profesor mora s klikom odobriti konec testiranja, da se izbriše iz baze, ne izbriše se
        // na connection close. Profesorja se opozarja na odobritev za konec testiranja na drugih straneh, če mu ne rata zapreti
        // na strani za testiranje. Za le-ta course se študent ne more ponovno čekirati niti po 2,5h, če je še vedno v ip_testingu
        // njegov zapis.

        // če teacher pošlje http request za katerokoli stran, se izbriše vse o ip-testingu iz tabel, ne da se ga vpraša; sicer
        // itak ne bi bilo več podatkov o že priključenih študentih, ko bi refreshal oz. ponovno prišel na ip_testing stran.
        // Ne, samo ko hoče spet zagnati testiranje, se prej vse izbriše.

        // NE: if at least 2 hours passed since last IP checking and if student isn't in ip_testing DB, allow it again
        $student_settings_record = StudentSettings::where('user_id', $student->id)->first();
        $last_time_present = $student_settings_record->last_time_present;//('user_id', $student->id)->first()->last_time_present;
        $already_been_checked = time() - $last_time_present < 1800;  // if he gained presence at any subject less than half out ago
        //$ip_testing_record = Ip_testing::where('user_id', $student->id)->first();
        if($already_been_checked){
            // he can only proceed to the last checked course if teacher has restarted checking
            $ip_testing_record = Ip_testing::where('user_id', $student->id)
                                        ->where('course_id', $student_settings_record->course_of_last_presence_id)
                                        ->first();
            // if the record isn't null, means that the previous checking is still running and student can't proceed
            if($ip_testing_record!=null){
                return "access_denied";
            }
            $course_of_last_presence = Course::where('id', $student_settings_record->course_of_last_presence_id)->first();
            // if the last checked course is currently checking (again), put it in array as the only course that student can check
            if($course_of_last_presence->isCurrentlyChecking){
                $coursesChecking = [$course_of_last_presence];
            }else return "access_denied";
        }else{
            // if there's more than half hour since last checking, he can gain presence at any course except the ones he already has
            $student_ids_in_ip_testings = Ip_testing::where('user_id', $student->id)->pluck('id')->toArray();
            foreach($coursesChecking as $courseChecking){
                if(in_array($courseChecking->id, $student_ids_in_ip_testings)){
                    unset($coursesChecking[$courseChecking]);
                }
            }
        }

        //$already_been_checked = time()-$last_time_present<=7200 || $exists_ip_testing_record;
        //if($already_been_checked) return "access_denied";
        //dd(time());
        //$student_settings_record->update(["last_time_present" => time()/*Carbon::now()]);*/
        // if there are more than 1 course he's enrolled in checking at the moment', he must change the right one
       /* if(count($coursesChecking)==1){
            $course = $coursesChecking[0];

            // if professor's IP and student's IP don't match, continue on site normally; don't even let student know he screwed up
            if($course->ipForChecking != $request->ip()) return "access_denied";
            StudentSettings::where('user_id', $student->id)
                        ->update(["last_time_present" => time(), "course_of_last_presence_id"=> $course->id]);
            $webSocketToken = md5(uniqid());
            Ip_testing::create([
                'user_id' => $student->id,
                'course_id' => $course->id,
                'ip' => $request->ip(),
                'is_tester' => 0,
                'token' => $webSocketToken
            ]);
            return view('students.ipChecking', [
                'student' => $student,
                'token' => $webSocketToken
                //'ip' => $request->ip()
            ]);
        }
    }*/

    public function ipCheckingSuccess()
        // show a page that confirms IP was successfully checked
    {
        return view('students.ipCheckingSuccess');
    }

    /*public function courseCurrentlyChecking()
        // check if any course student is signed in is being checked for presence at the moment,
        // return which one if it is
    {
        $student = auth()->user();
        $coursesChecking = Course::where('user_id', $student->id)
                            ->where('isCurrentlyChecking', 1)
                            ->get();
        dd(count($coursesChecking));
    }

    /*
    public static function ipLoginValidation(Request $request, User $user)
        // returns false if student's IP in request doesn't match with the one in DB
    {
        $studentIP = StudentStatistics::where('user_id', $user->id)->first('ip_addresses')->ip_addresses;
        // if it isn't set yet, OK
        if($studentIP == null) return true;
        // return true only if his IP in DB matches with the one he's currently using
        $ipMatches = str_contains($studentIP, sha1($request->ip()));
        return $ipMatches;
    }*/

    /*public function emailEnding($email)
    {
        $ending = "";
        $atSignAppeared = false;
        for($i=0; $i<strlen($email); $i++){
            $currentChar = substr($email, $i, 1);
            if($currentChar == "@") $atSignAppeared = true;
            if($atSignAppeared) $ending.= $currentChar;
        }
        return $ending;
    }*/
}
