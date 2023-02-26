<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\StudentSettings;
use App\Models\CoursesUser;//CourseStudent;

class StudentController extends Controller
{
    public function mainpage(/*Request $request*/)
    {
        // check if teacher is currently checking course

        Course::courseCurrentlyChecking();
        // if student searched in search bar for matching courses, the request has limitations:
        /*if($request->has('limitations')){
            $courses = Course::findMatching($request->limitations);
        // if this was a usual arrival on the page, we retrieve all courses the student is signed in
        }else{*/
        $student = auth()->user();
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

    public function showIpForm(Request $request)
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
    }

    public function ipChecking(Request $request)
    {
        return view('students.ipChecking', [
            'student' => auth()->user(),
            'ip' => $request->ip()
        ]);
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
