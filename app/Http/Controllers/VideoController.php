<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\User;

class VideoController extends Controller
{

    public function loadHomepage(){
        // display homepage on which user selects a video
        return view('videos.home', [
            'videos' => Video::all()
        ]);
    }

    public function uploadForm()
    {
        return view('videos.upload');
    }

    public function store(Request $request){
        // method stores new user's video to database

        // first check if form valid

        $videoForm = $request->validate([
            'title' => 'required',
            ''
        ])
        /*Video::create([
            'title' => 'FirstVideo2',
            'author' => User::find(1)->name,
            'description' => 'Fun video for watching',
            'views' => 0,
            'path' => '/fake2.mp4',
            'user_id' => 1,
            'genre' => 'fun'
        ]);*/
    }

    public function play(Video $video){
        // send a page that contains the video tag with source of the selected video
        dd($video->author);
    }
}
