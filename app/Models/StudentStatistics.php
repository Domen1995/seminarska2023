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
        // returns false if student's IP in request doesn't match with the one in DB
    {
        $studentIP = StudentStatistics::where('user_id', $user->id)->first('ip_addresses')->ip_addresses;
        // if it isn't set yet, OK
        if($studentIP == null) return true;
        // return true only if his IP in DB matches with the one he's currently using
        $ipMatches = str_contains($studentIP, sha1($request->ip()));
        return $ipMatches;
    }
}
