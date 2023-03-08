<?php

namespace App\Models;

use Illuminate\Http\Request;
use App\Models\StudentSettings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Logged_in_users extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'session_id'];

    public static function exists_another_session(User $user, Request $request)
        // compares if there exists another session id with the same user id and creates new record if it doesn't exist at all
    {
        $current_session_id = $request->session()->getId();
        $existing_logged_in_user = self::where('user_id', $user->id)->first();
        if($existing_logged_in_user!=null){
            if($existing_logged_in_user->session_id != $current_session_id){
                StudentSettings::where('user_id', $user->id)->increment("logged_on_multiple_devices", 1);
                // logout user
                auth()->logout();

                // security
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return true;
            }
            return false;
        }
        Logged_in_users::create([
            'user_id' => $user->id,
            'session_id' => $current_session_id
        ]);
        return false;
    }
}
