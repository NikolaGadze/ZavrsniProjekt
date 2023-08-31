<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'user_id', 'city_id'];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function courses()
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'instructor_id');
    }

}
