<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'instructor_id'];


    public function instructors()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class,'course_id');
    }
}
