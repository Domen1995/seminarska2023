<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\CoursesUser;//CourseStudent;

class StudentController extends Controller
{
    public function mainpage(/*Request $request*/)
    {
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

    public function enrollReq(Request $request, Course $course)
    {
        $user = auth()->user();
        CoursesUser::create([
            'user_id' => $user->id,
            'course_id' => $course->id
        ]);

        return back()->with('message', 'Teacher is noted to let you in');
    }

    public function selfProfile()
    {
        return view('students.selfProfile');
    }

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
