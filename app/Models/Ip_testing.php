<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ip_testing extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'is_tester',
        'ip',
        'websocketId',
    ];
}
