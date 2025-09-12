<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
        "start_date",
        "end_date",
        "event_start_date",
        "event_end_date",
        "location",
        "image",
        "fees",
        "capacity",
    ];

    protected $casts = [
        'start_date' => 'date:d-m-Y',
        'end_date' => 'date:d-m-Y',
        'event_start_date' => 'date:d-m-Y',
        'event_end_date' => 'date:d-m-Y',
    ];

    

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function users(){
        return $this->belongsToMany(User::class, "event_users")->withPivot('status')->withTimestamps();
    }

    public function event_queries(){
        return $this->hasMany(EventQuery::class);
    }
}
