<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'country_id'];

    public function countries()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function instructors()
    {
        return $this->hasMany(Instructor::class, 'city_id');
    }
}
