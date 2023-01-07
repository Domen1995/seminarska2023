<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\User;

class VideoController extends Controller
{

    public function homepage(){
        // display homepage on which user selects a video
        return view('videos.home', [
            'videos' => Video::all()
        ]);
    }

    public function watch(Video $video){
        // sends a page that contains the video tag with source of the selected video
        return view('videos.watch', [
            'video' => $video
        ]);
    }

    public function serveChunk(Video $video, Request $request)
    {
        // read from request header at which byte should this chunk start
        $startingPositionUnfiltered = $request->header('range');

        // create chunk and response headers
        $chunk = Video::createChunk($video, $startingPositionUnfiltered);
        // send the chunk of video
        echo $chunk;
    }
}
