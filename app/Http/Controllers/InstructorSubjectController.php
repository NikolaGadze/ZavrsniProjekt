<?php

namespace App\Http\Controllers;

use App\Models\InstructorSubject;
use Illuminate\Http\Request;

class InstructorSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return InstructorSubject::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return InstructorSubject::create([
            'instructor_id'=> $request->instructor_id,
            'subject_id'=> $request->subject_id
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(InstructorSubject $instructorSubject)
    {
        return $instructorSubject;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InstructorSubject $instructorSubject)
    {
        return $instructorSubject->update([
            'instructor_id'=> $request->instructor_id,
            'subject_id'=> $request->subject_id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InstructorSubject $instructorSubject)
    {
        return $instructorSubject->delete();
    }
}
