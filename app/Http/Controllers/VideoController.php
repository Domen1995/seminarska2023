<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use App\Models\Course;
use App\Models\CoursesUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VideoController extends Controller
{



    /*public function courseVideos(Course $course)
        // show all videos of the selected course to the author and also course manager if user is the owner
    {
        $videos = Video::where('course_id', $course->id)->paginate(24);
        $user = auth()->user();
        // get all students that requested for enrollments in this course, if it's owned by this user and if their status is "requested"
        if($user->isTeacher && $user->id == $course->user_id){
            $studentsToEnroll = User::whereIn('id', CoursesUser::where('course_id', $course->id)
                                                                ->where('status', 'requested')->get('user_id'))
                                            ->get();
        }
        return view('videos.courseVideos', [
            'videos' => $videos,
            'course' => $course,
            'user' => auth()->user(),
            'studentsToEnroll' => $studentsToEnroll
        ]);
    }*/

    /*
    public function courseVideos(Course $course)
    {
        $user = auth()->user();
        // check if user is really enrolled
        $enrollment = CoursesUser::where('course_id', $course->id)
                                    ->where('user_id', $user->id)
                                    ->where('status', 'enrolled')
                                    ->first();
        if($enrollment==null) return back('message', "You're not even enrolled to this course");
        $videos = Video::where('course_id', $course->id)->paginate(24);
        return view('videos.courseVideos', [
            'videos' => $videos,
            'course' => $course,
            'user' => $user
        ]);
    }*/

    public function homepage(Request $request){
        // display homepage on which user selects a video
        if(isset($request->limitations)){
            $videos = Video::findMatching($request->limitations);
        }else{
            $videos = Video::latest();
        }

        if(isset($request->genre)){
            $videos = Video::filterByGenre($request->genre);
        }

        // if user logged in, send his data to view
        $user = null;
        if(auth()->check()){
            $user = User::find((auth()->id()));
        }
        return view('videos.home', [
            //'videos' => Video::latest()->paginate(1), //Video::all(),
            'videos' => $videos->paginate(1),
            'user' => $user
        ]);
    }

    public function watch(Video $video, Course $course){
        // sends a page that contains the video tag with source of the selected video
        $user = auth()->user();
        // if user is the teacher who made the video, he will be allowed to watch
        $watchPermission = false;
        if($user->id == $video->user_id) $watchPermission = true;

        // increment number of views of the video, if clicked by a student

        if(!auth()->user()->isTeacher){
            $courseUser = CoursesUser::where('user_id', $user->id)->first();
            // if the value of screwUps minus presences is non-negative, let him watch the video
            $studentCredits = $courseUser->presences - $courseUser->screwUps;
            if($studentCredits >=0) $watchPermission = true;
            else{
                return back()->with("message", "All videos will be unlocked if you'll be at the lectures at the time of next ".(-$studentCredits)." presence checkings.");
            }
            $video->update([
                'views' => $video->views +1
            ]);
        }
        if(!$watchPermission) return back()->with("message", "You're not enrolled to watch the video");
        return view('videos.watch', [
            'video' => $video
        ]);
    }

    public function serveChunk(Video $video, Request $request)
        // sends part of video from requested starting point
    {
        // read from request header at which byte should this chunk start
        $startingPositionUnfiltered = $request->header('range');

        // create chunk and response headers
        $chunk = Video::createChunk($video, $startingPositionUnfiltered);
        // send the chunk of video
        echo $chunk;
        //flush();
    }

    public function test2()
    {
        return view('test');
    }
}

?>
