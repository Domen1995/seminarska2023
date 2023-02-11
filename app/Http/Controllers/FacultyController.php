<?php

// FACULTY ENTITY NOT IN USE

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;

class FacultyController extends Controller
{
    public function register(Request $request)
    {
        $facultyData = $request->validate([
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'name' => ['required', Rule::unique('users', 'name'), 'min:5', 'max:20'],   // nickname
            'password' => ['required', 'min:7']
        ]);
        $facultyData["verificationCode"] = sha1(time());
        Faculty::create($facultyData);
        $GLOBALS['email'] = $request->email;
        Mail::send('mails.signup', ['name' => $request->name, 'verificationCode' => $facultyData['verificationCode']], function ($message) {
            $message->from('info@videosforpresent.com');
            //$message->sender('john@johndoe.com', 'John Doe');
            $message->to($GLOBALS['email']);
            /*$message->cc('john@johndoe.com', 'John Doe');
            $message->bcc('john@johndoe.com', 'John Doe');
            $message->replyTo('john@johndoe.com', 'John Doe');*/
            $message->subject('Registration');
            $message->priority(3);
            //$message->attach('pathToFile');
        });
    }
}
