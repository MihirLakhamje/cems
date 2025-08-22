<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventQuery extends Model
{
    protected $fillable = [
        'messages',
        'photo',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function event(){
        return $this->belongsTo(Event::class);
    }
}
