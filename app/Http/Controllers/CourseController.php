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

    public function create(Request $request)
    {
        $user = auth()->user();
        if(!$user->isTeacher) return "How can you have a course if you're not a teacher?";
        $courseData = $request->validate([
            'name' => ['required', 'min:5', 'max:50'],
            'faculty' => ['required', 'min:2', 'max:50']
        ]);
        $courseData['user_id'] = $user->id;
        Course::create($courseData);
        return redirect('/teachers/mainpage')->with('message', 'Course'. $request->name .'created successfully.');
    }
}
