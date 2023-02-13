<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'teacher',
        'faculty',
        'user_id'
    ];

    public static function findMatching($limitations)
    {
        // select all courses with name containing substring "limitations" or they belong to teacher whose name contains "limitations"
        return Course::where('name', 'like', "%".$limitations."%")
                        ->orWhereIn('user_id', User::where('name', 'like', '%'.$limitations.'%')->where('isTeacher', '1')->get('id'))->paginate(1);//->get();
    }
}
