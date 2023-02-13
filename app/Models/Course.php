<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'faculty',
        'user_id'
    ];

    public static function findMatching($limitations)
    {
        return Course::where('name', 'like', "%".$limitations."%")
                        ->orWhereIn('user_id', User::where('name', 'like', '%'.$limitations.'%')->where('isTeacher', '1')->get())->get();
    }
}
