<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable// implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verificationCode',
        'verified',
        'isTeacher'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function video(){
        return $this->hasMany(Video::class, 'user_id');
    }


    public static function emailEnding($email)
    {
        $ending = "";
        $atSignAppeared = false;
        for($i=0; $i<strlen($email); $i++){
            $currentChar = substr($email, $i, 1);
            if($currentChar == "@") $atSignAppeared = true;
            if($atSignAppeared) $ending.= $currentChar;
        }
        return $ending;
    }
    /*
    public static function sendMail($address)
    {
        $confirmationLink = BASEURL."/users/verifyMail/".$address;
        ini_set("SMTP","localhost");
        ini_set("smtp_port","3767");
        ini_set("sendmail_from","debela.lubenica@gmail.com");
        //ini_set("sendmail_path", "C:\wamp\bin\sendmail.exe -t");

        mail("tapewormerbinkosti@gmail.com", "Confirm registration", "Thanks for registering! Click the following link to proceed: ".$confirmationLink,
                'From: debela.lubenica@gmail.com\n');
    }*/
}
