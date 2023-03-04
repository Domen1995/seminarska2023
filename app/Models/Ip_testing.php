<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ip_testing extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'user_id',
        'is_tester',
        'ip',
        'websocketId',
        'token'
    ];

    public static function clear_DB_ip_data(Course $course)
    {
        //$ip_testing = Ip_testing::where('user_id', auth()->user()->id)->first();
        // NE: if disconnected WS is from the tester (teacher), delete all rows of this course in ip_testings table
        //if($ip_testing->is_tester){
        self::where('course_id', $course->id)->delete();
        //Course::where('id', $ip_testing->course_id)->update(['ipForChecking'=>null, 'isCurrentlyChecking'=>0]);
        $course->update(['ipForChecking'=>null, 'isCurrentlyChecking'=>0]);
        //}
    }
}
