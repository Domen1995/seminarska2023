<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'author', 'description', 'views', 'path'];

    public function user()
    {
        $this->belongsTo(User::class, 'user_id');
    }
}
