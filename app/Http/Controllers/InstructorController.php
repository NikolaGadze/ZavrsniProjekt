<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Instructor::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return Instructor::create([
            'title'=> $request->title,
            'user_id'=> $request->user_id,
            'city_id'=> $request->city_id
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Instructor $instructor)
    {
        return $instructor;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Instructor $instructor)
    {
        return $instructor->update([
            'title'=> $request->title,
            'user_id'=> $request->user_id,
            'city_id'=> $request->city_id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Instructor $instructor)
    {
        return $instructor->delete();
    }
}
