<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'teacher',
        'faculty',
        'user_id',
        'allowedEmails',
        'isCurrentlyChecking'
    ];

    public static function findMatching($limitations, $user)
    {
        // select all courses with name containing substring "limitations" or they belong to teacher whose name contains "limitations"
        if($user->isTeacher){
            return Course::where('name', 'like', "%".$limitations."%")
                        ->orWhereIn('user_id', User::where('name', 'like', '%'.$limitations.'%')->where('isTeacher', '1')->get('id'))->paginate(8);//->get();
        }else{
            $GLOBALS['studentsEmailEnding'] = User::emailEnding($user->email);
            $courses = Course::where('name', 'like', "%".$limitations."%")
                        ->orWhereIn('user_id', User::where('name', 'like', '%'.$limitations.'%')->where('isTeacher', '1')->get('id'))
                        ->where(function($query){
                            $query->where('allowedEmails', 'like', '%'.$GLOBALS['studentsEmailEnding'].'%')
                                    ->orWhereNull('allowedEmails');
                        });
            return $courses->paginate(8);
        }
    }

    public static function ipChecking($student)
        // check if any course student is signed in is being checked for presence at the moment,
        // return which one if it is
    {
        $coursesChecking = Course::where('isCurrentlyChecking', 1)
                            ->whereIn('id', CoursesUser::where('user_id', $student->id)->get('id'))
                            ->get();
        return $coursesChecking;
        if(count($coursesChecking)==0) return;
        if(count($coursesChecking)==1){
            $websocketToken = md5(uniqid());

        }
    }


}
