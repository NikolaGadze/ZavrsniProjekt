<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'user_id', 'city_id'];

    public function cities()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subjecs()
    {
        return $this->belongsToMany(Subject::class,'instructor_subjects','subject_id', 'instructor_id');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class,'instructor_courses','course_id', 'instructor_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'instructor_id');
    }

}
