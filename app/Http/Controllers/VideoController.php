<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VideoController extends Controller
{

    public function courseVideos(Course $course)
        // show all videos of the selected course
    {
        $videos = Video::where('course_id', $course->id)->paginate(24);
        return view('videos.courseVideos', [
            'videos' => $videos,
            'course' => $course,
            'user' => auth()->user()
        ]);
    }

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

    public function watch(Video $video){
        // sends a page that contains the video tag with source of the selected video

        // increment number of views of the video
        $video->update([
            'views' => $video->views +1
        ]);
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
    }
}
