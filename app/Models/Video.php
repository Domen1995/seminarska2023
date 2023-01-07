<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'author', 'description', 'views', 'path', 'user_id', 'genre'];

    public function user()
        // a certain video is owned by 1 user
    {
        $this->belongsTo(User::class, 'user_id');
    }
}
