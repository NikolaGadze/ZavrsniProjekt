<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return City::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return City::create([
            'name'=> $request->name,
            'country_id'=> $request->country_id
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {
        return $city;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, City $city)
    {
        return $city->update([
            'name'=> $request->name,
            'country_id' => $request->country_id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        return $city->delete();
    }
}
