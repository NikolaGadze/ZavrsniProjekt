<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Reservation;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
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


    public function searchInstructors(Request $request) {

        $user = Auth::user();

        if ($user) {

            $userRoleUser = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 2)->first();
            $userRoleInstructor = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 3)->first();

            if ($userRoleUser || $userRoleInstructor) {
                $search = $request->search;
                $query = DB::table('instructors as i')
                    ->join('users as u', 'i.user_id', '=', 'u.id')
                    ->join('cities as cit', 'i.city_id', '=', 'cit.id')
                    ->join('countries as cnt', 'cit.country_id', '=', 'cnt.id')
                    ->join('user_roles as ur', 'u.id', '=', 'ur.user_id')
                    ->join('courses as c', 'i.id', '=', 'c.instructor_id')
                    ->select('u.id as user_id', 'i.id as instructor_id', 'u.first_name as first_name', 'u.last_name as last_name', 'i.title as title', 'u.email as email', 'u.phone as phone','cit.name as city_name', 'cnt.name as country_name', 'c.name as course_name')
                    ->where('ur.role_id', 3)
                    ->where('u.status', 'Active')
                    ->where(function ($q) use ($search) {
                        $q->where('u.first_name', 'like', "%$search%")
                            ->orWhere('u.last_name', 'like', "%$search%")
                            ->orWhere('cit.name', 'like', "%$search%")
                            ->orWhere('cnt.name', 'like', "%$search%")
                            ->orWhere('c.name', 'like', "%$search%");
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

    public function reserveInstruction(Request $request) {

        $user = Auth::user();

        if ($user) {

            $userRoleUser = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 2)->first();
            $userRoleInstructor = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 3)->first();

            if ($userRoleUser || $userRoleInstructor) {

                $reservation = Reservation::create([
                    'instructor_id'=> $request->instructor_id,
                    'user_id'=> $user->id,
                    'course_id'=> $request->course_id,
                    'date'=> $request->date,
                    'location'=> $request->location,
                    'reservation_status'=> $request->reservation_status,
                    'price'=> $request->price
                ]);
            }
        } else {
            abort(401);
        }
    }

    public function createCourse(Request $request) {

        $user = Auth::user();

        if ($user) {

            $userRoleInstructor = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 3)->first();

            if ($userRoleInstructor) {
                $course = Course::create([
                    'name'=> $request->name,
                    'description'=> $request->description,
                    'instructor_id'=> $request->instructor_id
                ]);
            }
        } else {
            abort(401);
        }
    }


    public function getUserReservations(Request $request): JsonResponse
    {
        $user = Auth::user();

        if ($user) {
            $userRoleUser = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 2)->first();

            if ($userRoleUser) {
                $reservations = Reservation::where('user_id', $user->id)->get();

                return response()->json(['reservations' => $reservations]);
            } else {
                return response()->json(['message' => 'You do not have the required role for this action.'], Response::HTTP_FORBIDDEN);
            }
        } else {
            return response()->json(['message' => 'Unauthorized.'], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function getInstructorReservations(Request $request): JsonResponse
    {
        $user = Auth::user();

        if ($user) {
            $userRoleInstructor = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 3)->first();

            if ($userRoleInstructor) {

                $instructorId = DB::table('instructors')->where('user_id', $user->id)->value('id');

                if ($instructorId) {
                    $reservations = Reservation::where('instructor_id', $instructorId)->get();
                    return response()->json(['reservations' => $reservations]);
                } else {
                    return response()->json(['message' => 'Instructor not found.'], Response::HTTP_NOT_FOUND);
                }
            } else {
                return response()->json(['message' => 'You do not have the required role for this action.'], Response::HTTP_FORBIDDEN);
            }
        } else {
            return response()->json(['message' => 'Unauthorized.'], Response::HTTP_UNAUTHORIZED);
        }
    }


    public function manageReservations(Request $request, $id)
    {
        $user = Auth::user();

        if ($user) {

            $userRoleInstructor = DB::table('user_roles')
                ->where('user_id', $user->id)
                ->where('role_id', 3)
                ->first();

            if ($userRoleInstructor) {

                $reservation = Reservation::findOrFail($id);


                $reservation->update([
                    'reservation_status' => $request->reservation_status
                ]);

                return response()->json(['message' => 'Reservation status updated successfully']);
            } else {
                return response()->json(['message' => 'You do not have permission to manage reservations'], 403);
            }
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }



    public function showProfile(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $userRoleInstructor = $user->roles()->where('role_id', 3)->first();

            if ($userRoleInstructor) {
                $instructor = $user->instructors()->with('city.country')->first();

                if ($instructor) {
                    return response()->json([
                        'success' => true,
                        'data' => [
                            'email' => $user->email,
                            'first_name' => $user->first_name,
                            'last_name' => $user->last_name,
                            'phone' => $user->phone,
                            'status' => $user->status,
                            'title' => $instructor->title,
                            'city_name' => $instructor->city->name,
                            'country_name' => $instructor->city->country->name,
                        ]
                    ], 200);
                }
            } else {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'email' => $user->email,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'phone' => $user->phone,
                        'status' => $user->status
                    ]
                ], 200);
            }
        } else {
            abort(401);
        }
    }

    public function updateProfile(Request $request) {
        $user = Auth::user();

        $validated = $request->validate([

            'email' => [
                'required','email',
                Rule::unique('users')->ignore($user->id),
            ],
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => [
                'required','string',
                Rule::unique('users')->ignore($user->id),
            ],

        ]);
        return $user->update([
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,

        ]);

    }

    public function getAllUserWithRoles(Request $request) {
        $user = Auth::user();

        if ($user) {
            $userRoleAdmin = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 4)->first();

            if($userRoleAdmin) {
                $query = DB::table('users AS u')
                    ->select('u.id As user_id', 'u.first_name', 'u.last_name', 'u.email', 'r.name AS role_name',)
                    ->join('user_roles AS ur', 'u.id', '=', 'ur.user_id')
                    ->join('roles AS r', 'r.id', '=', 'ur.role_id')
                    ->where('u.status', 'Active')
                    ->where('ur.role_id', '=', 2)
                    ->orWhere('ur.role_id', '=',3);

                $data = $query->paginate(9);

                return response()->json($data);
            } else {
                abort(401);
            }

        } else {
            abort(403);
        }
    }


    public function deleteUsers($userId)
    {
        $user = Auth::user();

        if ($user) {
            $userRoleAdmin = DB::table('user_roles')
                ->where('user_id', $user->id)
                ->where('role_id', 4)
                ->first();

            if ($userRoleAdmin) {
                $userRoleUser = DB::table('user_roles AS ur')
                    ->where('ur.user_id', '=', $userId)
                    ->where('ur.role_id', '=', 2)
                    ->first();

                $userRoleInstructor = DB::table('user_roles AS ur')
                    ->where('ur.user_id', '=', $userId)
                    ->where('ur.role_id', '=', 3)
                    ->first();

                if ($userRoleUser) {
                    DB::table('user_roles')->where('user_id', $userId)->delete();
                    DB::table('users')->where('id', $userId)->delete();
                } elseif ($userRoleInstructor) {
                    DB::table('user_roles')->where('user_id', $userId)->delete();
                    $instructor = DB::table('instructors')->where('user_id', $userId)->first();

                    if ($instructor) {
                        $instructorId = $instructor->id;
                        DB::table('reservations')->where('instructor_id', $instructorId)->delete();
                        DB::table('courses')->where('instructor_id', $instructorId)->delete();
                        DB::table('instructors')->where('id', $instructorId)->delete();
                    }

                    DB::table('users')->where('id', $userId)->delete();
                }
            } else {
                abort(401);
            }
        } else {
            abort(403);
        }
    }

    public function getAllCourses(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $userRoleAdmin = DB::table('user_roles')
                ->where('user_id', $user->id)
                ->where('role_id', 4)
                ->first();

            if ($userRoleAdmin) {

                $query = DB::table('instructors as i')
                    ->join('users as u', 'i.user_id', '=', 'u.id')
                    ->join('courses as c', 'i.id', '=', 'c.instructor_id')
                    ->select(
                        'c.id as course_id',
                        'c.name as course_name',
                        'c.description as course_description',
                        'i.id as instructor_id',
                        'i.title as instructor_title',
                        'u.id as user_id',
                        'u.first_name as first_name',
                        'u.last_name as last_name',
                        'u.email as user_email',
                        'u.phone as user_phone'
                    );

                $data = $query->paginate(9);

                if ($data->count() == 0) {
                    return response()->json(['message' => 'No results found.'], 404);
                }

                return response()->json($data);

            } else {
                abort(401);
            }
        } else {
            abort(403);
        }
    }

    public function deleteCourses(Request $request, $course_id)
    {
        $user = Auth::user();

        if ($user) {

            $userRoleAdmin = DB::table('user_roles')
                ->where('user_id', $user->id)
                ->where('role_id', 4)
                ->first();

            if ($userRoleAdmin) {

                $course = DB::table('courses')->where('id', $course_id)->first();

                if ($course) {

                    $instructorId = $course->instructor_id;

                    DB::table('reservations')->where('course_id', $course_id)->delete();
                    DB::table('courses')->where('id', $course_id)->delete();

                } else {
                    abort(404);
                }
            } else {
                abort(401);
            }
        } else {
            abort(403);
        }

    }



}
