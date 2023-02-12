<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function mainpage()
    {
        return view('students.mainpage', [

        ]);
    }

    public function selfProfile()
    {
        return view('students.selfProfile');
    }
}
