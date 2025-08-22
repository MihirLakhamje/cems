<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    // $fillable gives access to the user to fill these fields
    protected $fillable = [
        'name',
        'head_name',
        'head_email',
        'head_phone',
        'fest_type',
        'is_active',
    ];

    // Define the fest types as constants for better maintainability
    public function getFestTypeNameAttribute()
    {
        return match ($this->fest_type) {
            'dept_fest'   => 'Departmental Fest',
            'association' => 'Association',
            'clg_fest'    => 'College Fest',
            default       => 'Unknown Fest',
        };
    }

    // Departments can have many users
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Departments can have many events
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
