<?php

namespace App\Http\Controllers;

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
        $courses = Course::whereIn('id', CoursesUser::select('course_id')->where('user_id', auth()->user()->id)->get());

        return view('students.mainpage', [
            // select all courses that student is signed in
            'courses' => $courses
        ]);
    }

    public function selfProfile()
    {
        return view('students.selfProfile');
    }
}
