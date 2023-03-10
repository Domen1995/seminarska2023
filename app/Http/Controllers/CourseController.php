<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CoursesUser;
use Illuminate\Http\Request;
use App\Models\TeacherSettings;

class CourseController extends Controller
{
    public function search(Request $request)
    {
        $user = auth()->user();
        $courses = Course::findMatching($request->limitations, $user);
        //if(auth()->user()->isTeacher){
        if($user->isTeacher){
            return view('teachers.mainpage', [
                // select all courses that student is signed in
                'user' => $user,
                'courses' => $courses
            ]);
        }else{
            $coursesUsers = CoursesUser::where('user_id', auth()->user()->id)->get();
            if (count($coursesUsers)==0) $coursesUsers = null;
            else $coursesUsers = CoursesUser::associateCourseId($coursesUsers);
            return view('students.mainpage', [
                // select all courses that student is signed in
                'user' => $user,
                'courses' => $courses,
                'coursesUsers' => $coursesUsers
            ]);
        }
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        if(!$user->isTeacher) return "How can you have a course if you're not a teacher?";
        $courseData = $request->validate([
            'name' => ['required', 'min:5', 'max:50'],
            'faculty' => ['required', 'min:2', 'max:50'],
            //'allowedEmails' => $allowedEmails//TeacherSettings::where('user_id', $user->id)->first()->allowedEmails
        ]);
        $courseData['user_id'] = $user->id;
        $courseData['teacher'] = $user->name;
        $allowedEmails = TeacherSettings::where('user_id', $user->id)->first()->allowedEmails;
        $courseData['allowedEmails'] = $allowedEmails;
        Course::create($courseData);
        return redirect('/teachers/mainpage')->with('message', 'Course'. $request->name .'created successfully.');
    }
}
