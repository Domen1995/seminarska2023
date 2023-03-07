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

    public static function courses_available_to_student_ip_check($student, $coursesChecking)
        // filters the courses that student's enrolled in and are currently checking
        // and returns only the ones he doesn't have a record in ip_checking, excludes all except maybe 1 if
        // he has checked in the last half hour, and excludes all to which his IP address doesn't match
    {
        $student_settings_record = StudentSettings::where('user_id', $student->id)->first();
        $last_time_present = $student_settings_record->last_time_present;//('user_id', $student->id)->first()->last_time_present;
        $already_been_checked = time() - $last_time_present < 1800;  // if he gained presence at any subject less than half out ago
        //$ip_testing_record = Ip_testing::where('user_id', $student->id)->first();
        if($already_been_checked){
            // he can only proceed to the last checked course if teacher has restarted checking
            $ip_testing_record = Ip_testing::where('user_id', $student->id)
                                        ->where('course_id', $student_settings_record->course_of_last_presence_id)
                                        ->first();
            // if the record isn't null, means that the previous checking is still running and student can't proceed
            if($ip_testing_record!=null){
                return [];
            }
            $course_of_last_presence = Course::where('id', $student_settings_record->course_of_last_presence_id)->first();
            // if the last checked course is currently checking (again), put it in array as the only course that student can check
            if($course_of_last_presence->isCurrentlyChecking){
                $coursesChecking = [$course_of_last_presence];
            }else return [];
        }else{
            // if there's more than half hour since last checking, he can gain presence at any course except the ones he already has
            // and also exclude the ones which ip don't match
            $student_ids_in_ip_testings = Ip_testing::where('user_id', $student->id)->pluck('id')->toArray();
            foreach($coursesChecking as $courseChecking){
                if(in_array($courseChecking->id, $student_ids_in_ip_testings) || $student->ip() != $courseChecking->ipForChecking){
                    unset($coursesChecking[$courseChecking]);
                }
            }
        }
        return $coursesChecking;
    }
}

?>
