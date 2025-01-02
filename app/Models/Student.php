<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'city', 'password'];

    // Ensure the password is hashed when stored
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($student) {
            $student->password = bcrypt($student->password);
        });
        static::updating(function ($student) {
            $student->password = bcrypt($student->password);
        });
    }
}

