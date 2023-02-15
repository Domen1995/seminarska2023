<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'allowedEmails',
        'ip_addresses_for_checking'
    ];
}
