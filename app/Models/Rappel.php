<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rappel extends Model
{
    use HasFactory;
    protected $fillable = ['date', 'texte', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

