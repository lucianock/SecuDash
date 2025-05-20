<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinkedinPost extends Model
{
    //
    protected $fillable = [
        'content',
        'url',
        'author', // Agregar si lo necesitás
        'published_at', // Agregar si lo necesitás
    ];
}
