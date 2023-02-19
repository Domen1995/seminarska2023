<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentStatistics extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_addresses',
        'screwUps',
        'presences'
    ];


    public static function ipLoginValidation(Request $request, User $user)
        // sets session attributes and redirects according to match between student's IP in request and with the one in DB
    {
        $studentIP = StudentSettings::where('user_id', $user->id)->first('ip_addresses')->ip_addresses;
        // if it isn't set, redirect student to the page where he will confirm or reject current IP to be permanent
        if($studentIP == null){
            session(["ipStatus" => "noIP"]);
            return redirect('/students/ipForm');
        }
        // if IP that student's currently using is in DB under his name:
        if($ipMatches = str_contains($studentIP, sha1($request->ip()))){
            /*
            if(substr_count($studentIP, ",")==1) session(["ipStatus" => "one"]);
            // if two:*/
            // how many IPs has student registered:
            session(["ipsRegistered" => substr_count($studentIP, ",")]);
            session(["ipStatus" => "ok"]);
            return redirect('/students/mainpage')->with('message', 'Welcome back, '.$user->name);
        }
        // if current IP not valid, still remember how many IPs he's registered:
        session(["ipsRegistered" => substr_count($studentIP, ",")]);
        session(["ipStatus" => "wrongIP"]);  // meaning that user is on different router than the one he set as his home router
        return redirect('/students/mainpage')->with('message', 'Welcome back, '.$user->name);
        //if($studentIP == null) return true;
        // return true only if his IP in DB matches with the one he's currently using
        //$ipMatches = str_contains($studentIP, sha1($request->ip()));
        //return $ipMatches;
    }
}
