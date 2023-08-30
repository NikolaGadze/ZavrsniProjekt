<?php

namespace App\Http\Controllers;

use App\Models\InstructorCourse;
use Illuminate\Http\Request;

class InstructorCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return  InstructorCourse::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return InstructorCourse::create([
            'instructor_id'=> $request->instructor_id,
            'course_id'=> $request->course_id
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(InstructorCourse $instructorCourse)
    {
        return $instructorCourse;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InstructorCourse $instructorCourse)
    {
        return $instructorCourse->update([
            'instructor_id'=> $request->instructor_id,
            'course_id'=> $request->course_id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InstructorCourse $instructorCourse)
    {
        return $instructorCourse->delete();
    }
}
