<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::apiResource('users', UserController::class);
Route::apiResource('roles', RoleController::class);
Route::apiResource('user-roles', UserRoleController::class);
Route::apiResource('countries', CountryController::class);
Route::apiResource('cities', CityController::class);
Route::apiResource('instructors', InstructorController::class);
Route::apiResource('courses', CourseController::class);
Route::apiResource('reservations', ReservationController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function (){
    Route::post('registerAsUser', [AuthController::class, 'registerAsUser']);
    Route::post('registerAsInstructor', [AuthController::class, 'registerAsInstructor']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('logout', [AuthController::class, 'logout'])
        ->middleware('auth:sanctum');
    Route::get('user', [AuthController::class, 'user'])
        ->middleware('auth:sanctum');
    Route::get('search-instructors', [AuthController::class, 'searchInstructors'])
        ->middleware('auth:sanctum');
    Route::post('reserve-instruction', [AuthController::class, 'reserveInstruction'])
        ->middleware('auth:sanctum');
    Route::post('create-course', [AuthController::class, 'createCourse'])
        ->middleware('auth:sanctum');
    Route::get('get-user-reservations', [AuthController::class, 'getUserReservations'])
        ->middleware('auth:sanctum');
    Route::get('get-instructor-reservations', [AuthController::class, 'getInstructorReservations'])
        ->middleware('auth:sanctum');
    Route::put('manage-reservations/{id}', [AuthController::class, 'manageReservations'])
        ->middleware('auth:sanctum');
    Route::get('show-profile', [AuthController::class, 'showProfile'])
        ->middleware('auth:sanctum');
    Route::put('update-profile', [AuthController::class, 'updateProfile'])
        ->middleware('auth:sanctum');
    Route::get('all-user-roles', [AuthController::class, 'getAllUserWithRoles'])
        ->middleware('auth:sanctum');
    Route::delete('delete-users/{user_id}', [AuthController::class, 'deleteUsers'])
        ->middleware('auth:sanctum');
    Route::get('all-courses', [AuthController::class, 'getAllCourses'])
        ->middleware('auth:sanctum');
    Route::delete('delete-courses/{course_id}', [AuthController::class, 'deleteCourses'])
        ->middleware('auth:sanctum');
});
