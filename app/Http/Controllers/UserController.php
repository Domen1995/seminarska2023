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
        return view('users.upload');
    }

    public function store(Request $request){
        // method stores new user's video to database

        // first check if user sent valid data

        $formData = $request->validate([
            'title' => 'required',
            'videoFile' => ['required', 'max:300000'],   // video mustn't exceed 300 MB
            'description' => 'nullable',
            'genre' => 'nullable'
        ]);

        // user who uploaded the video
        $user = User::find(1);

        /*$formData = [
            'genre' => 'entertainment',
            'title' => $request->title,
            'author' => $user->name,
            'description' => $request->description,
            'views' => 0,
            'path' => $video->store('videos'),
            'user_id' => 3
        ];*/

        // put video into a variable $video
        $video = $request->file('videoFile');

        // store video and put its path into 'videoPath'
        $formData['path'] = $video->store('videos', 'public');
        $formData['user_id'] = $user->id;


        // create other video metadata
        $formData['author'] = $user->name;
        $formData['genre'] = 'music';
        $formData['views'] = 0;


        // insert video metadata into DB
        Video::create($formData);

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
