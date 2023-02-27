<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use SimpleMail;

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

    public static function sendVerificationMail()
    {
        $send = SimpleMail::make()
            ->setTo('89181150@student.upr.si', 'Tape')
            ->setFrom('noreply@videosforpresent.com', 'Admin')
            ->setSubject('Verify registration')
            ->setMessage('Please click link to verify')
            //->setHtml()
            ->setWrap(100)
            ->send();

        echo ($send) ? "Email sent successfully" : "Not sent";
    }

    public static function verificationMailMessage($name, $verificationCode)
    {
        return 'Please click the following link to verify your email address'/*, $name: <a href="https://'.$_SERVER["SERVER_ADDR"].'/users/verifyMail?n='.$name.'&c='.$verificationCode-'">https://'.$_SERVER["SERVER_ADDR"].'/users/verifyMail?n={{$name}}&c={{$verificationCode}}</a>
            {{--"https://localhost/seminarska2023/public--}}
            <br>
            Until email is verified, this link will delete the record from database: <a href="https://'.$_SERVER["SERVER_ADDR"].'/users/deleteBeforeVerified?c='.$verificationCode.'">https://'.$_SERVER["SERVER_ADDR"].'/users/deleteBeforeVerified?c={{$verificationCode}}</a>';*/;
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
