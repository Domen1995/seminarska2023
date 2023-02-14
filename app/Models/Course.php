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
        'allowedEmails'
    ];

    public static function findMatching($limitations, $user)
    {
        // select all courses with name containing substring "limitations" or they belong to teacher whose name contains "limitations"
        if($user->isTeacher){
            return Course::where('name', 'like', "%".$limitations."%")
                        ->orWhereIn('user_id', User::where('name', 'like', '%'.$limitations.'%')->where('isTeacher', '1')->get('id'))->paginate(1);//->get();
        }else{
            $GLOBALS['studentsEmailEnding'] = User::emailEnding($user->email);
            $courses = Course::where('name', 'like', "%".$limitations."%")
                        ->orWhereIn('user_id', User::where('name', 'like', '%'.$limitations.'%')->where('isTeacher', '1')->get('id'))
                        ->where(function($query){
                            $query->where('allowedEmails', 'like', '%'.$GLOBALS['studentsEmailEnding'].'%')
                                    ->orWhereNull('allowedEmails');
                        });
            return $courses->paginate(1);
        }
    }
}
