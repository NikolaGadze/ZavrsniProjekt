<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Course::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return Course::create([
            'name'=> $request->name,
            'description'=> $request->description
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return $course;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        return $course->update([
            'name'=> $request->name,
            'description'=> $request->description
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        return $course->delete();
    }
}
