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
        // send a page that contains the video tag with source of the selected video
        dd($video->author);
    }
}
