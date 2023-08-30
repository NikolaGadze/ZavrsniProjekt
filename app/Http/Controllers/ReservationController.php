<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Reservation::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return Reservation::create([
            'instructor_id'=> $request->instructor_id,
            'user_id'=> $request->user_id,
            'course_id'=> $request->course_id,
            'date'=> $request->date,
            'location'=> $request->location,
            'reservation_status'=> $request->reservation_status,
            'price'=> $request->price
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        return $reservation;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        return $reservation->update([
            'instructor_id'=> $request->instructor_id,
            'user_id'=> $request->user_id,
            'course_id'=> $request->course_id,
            'date'=> $request->date,
            'location'=> $request->location,
            'reservation_status'=> $request->reservation_status,
            'price'=> $request->price
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        return $reservation->delete();
    }
}
