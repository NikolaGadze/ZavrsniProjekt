<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Instructor;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registerAsUser(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string|unique:users',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
        ]);

        $userRole = UserRole::create([
            'user_id' => $user->id,
            'role_id' => 2
        ]);

        return $user;
    }

    public function registerAsInstructor(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string|unique:users',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'title' => 'required|string',
        ]);


        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
        ]);

        $userRole = UserRole::create([
            'user_id' => $user->id,
            'role_id' => 3
        ]);

        $instructor = Instructor::create([
            'title' => $request->title,
            'user_id' => $user->id,
            'city_id' => $request->city_id,
        ]);


        return $user;
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('pziToken')->plainTextToken;

        return $token;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer'
        ]);
    }

    public function user(Request $request)
    {
        $user = Auth::user();

        //$user->load('role');

        return $user;
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response('Success');
    }

    /*public function searchInstructors(Request $request) {
        $user = Auth::user();
        if ($user) {
            $userRoleUser = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 2)->first();
            $userRoleInstructor = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 3)->first();
            $userRoleAdmin = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 4)->first();

            if ($userRoleUser || $userRoleInstructor || $userRoleAdmin) {
                $search = $request->search;
                $query = DB::table('instructors as i')
                    ->join('users as u', 'i.user_id', '=', 'u.id')
                    ->join('cities as cit', 'i.city_id', '=', 'cit.id')
                    ->join('countries as cnt', 'cit.country_id', '=', 'cnt.id')
                    ->join('instructor_subjects as is', 'i.id', '=', 'is.instructor_id')
                    ->join('subjects as s', 'is.subject_id', '=', 's.id')
                    ->join('user_roles as ur', 'u.id', '=', 'ur.user_id')
                    ->join('instructor_courses as ic', 'i.id', '=', 'ic.instructor_id')
                    ->join('courses as c', 'ic.course_id', '=', 'c.id')
                    ->select('u.id as user_id', 'i.id as instructor_id', 'u.first_name as first_name', 'u.last_name as last_name', 'i.title as title', 'u.email as email', 'u.phone as phone','cit.name as city_name', 'cnt.name as country_name', 's.name as subject_name', 'c.name as course_name')
                    ->where('ur.role_id', 3)
                    ->where(function ($q) use ($search) {
                        $q->where('u.first_name', 'like', "%$search%")
                            ->orWhere('u.last_name', 'like', "%$search%")
                            ->orWhere('cit.name', 'like', "%$search%")
                            ->orWhere('cnt.name', 'like', "%$search%")
                            ->orWhere('c.name', 'like', "%$search%")
                            ->orWhere('s.name', 'like', "%$search%");
                    });

                $data = $query->paginate(9);

                if ($data->count() == 0) {
                    return response()->json(['message' => 'No results found for ' . $search], 404);
                }

                return response()->json($data);
            }
        } else {
            abort(401);
        }
    }*/
    public function searchInstructors(Request $request) {
        $user = Auth::user();
        if ($user) {
            $userRoleUser = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 2)->first();
            $userRoleInstructor = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 3)->first();
            $userRoleAdmin = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 4)->first();

            if ($userRoleUser || $userRoleInstructor || $userRoleAdmin) {
                $search = $request->search;
                $query = DB::table('instructors as i')
                    ->join('users as u', 'i.user_id', '=', 'u.id')
                    ->join('cities as cit', 'i.city_id', '=', 'cit.id')
                    ->join('countries as cnt', 'cit.country_id', '=', 'cnt.id')
                    ->join('user_roles as ur', 'u.id', '=', 'ur.user_id')
                    ->select('u.id as user_id', 'i.id as instructor_id', 'u.first_name as first_name', 'u.last_name as last_name', 'i.title as title', 'u.email as email', 'u.phone as phone','cit.name as city_name', 'cnt.name as country_name')
                    ->where('ur.role_id', 3)
                    ->where(function ($q) use ($search) {
                        $q->where('u.first_name', 'like', "%$search%")
                            ->orWhere('u.last_name', 'like', "%$search%")
                            ->orWhere('cit.name', 'like', "%$search%")
                            ->orWhere('cnt.name', 'like', "%$search%");
                    });

                $data = $query->paginate(9);

                if ($data->count() == 0) {
                    return response()->json(['message' => 'No results found for ' . $search], 404);
                }

                return response()->json($data);
            }
        } else {
            abort(401);
        }
    }
}
