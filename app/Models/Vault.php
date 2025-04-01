<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vault extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'host',
        'username',
        'password',
        'notes',
        'user_id'
    ];

    protected $hidden = ['password'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

