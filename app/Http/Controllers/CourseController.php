<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function search(Request $request)
    {
        $courses = Course::findMatching($request->limitations);
        if(auth()->user()->isTeacher){
            return view('teachers.mainpage', [
                // select all courses that student is signed in
                'courses' => $courses
            ]);
        }else{
            return view('students.mainpage', [
                // select all courses that student is signed in
                'courses' => $courses
            ]);
        }
    }
}
