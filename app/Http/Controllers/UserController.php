<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function usersProfile(User $user)
        // display a requested profile to a user
    {
        return view('users.profile', [
            'user' => $user
        ]);
    }

    public function uploadForm()
        // show form for uploading a video
    {
        return view('videos.upload');
    }

    public function store(Request $request){
        // method stores new user's video to database

        // first check if form valid

        $videoForm = $request->validate([
            'title' => 'required',
            'videoFile' => 'required'
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
}
