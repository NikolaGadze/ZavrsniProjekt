<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UserRole::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return UserRole::create([
            'user_id'=> $request->user_id,
            'role_id'=> $request->role_id,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(UserRole $userRole)
    {
        return $userRole;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserRole $userRole)
    {
        return $userRole->update([
            'user_id'=> $request->user_id,
            'role_id'=> $request->role_id,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserRole $userRole)
    {
        return $userRole->delete();
    }
}
