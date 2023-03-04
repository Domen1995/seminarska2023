<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursesUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id'
    ];

    public static function associateCourseId($coursesUsers)
    {
        // associate course ID with each of CoursesUser model objects in array $coursesUsers
        $associated = [];
        foreach($coursesUsers as $coursesUser){
            $id = $coursesUser->course_id;
            $associated[$id] = $coursesUser;
            //dd($associated[$id]);
        }
        return $associated;
            /*
            $coursesUser->course_id;
            dd($coursesUser->course_id);*/
    }

    public static function reduce_screwUps($students_in_course, $number)
    {
        //$coursesUser = self::whereIn('user_id', $students);
        // student is an instance of courses_user model
        foreach($students_in_course as $student_in_course){
            $student_in_course->screwUps = $student_in_course->screwUps - $number;
            $student_in_course->save();
        }
    }
}
