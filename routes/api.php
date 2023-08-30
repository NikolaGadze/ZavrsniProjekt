<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\InstructorCourseController;
use App\Http\Controllers\InstructorSubjectController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubjectController;
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
Route::apiResource('subjects', SubjectController::class);
Route::apiResource('instructors', InstructorController::class);
Route::apiResource('instructor-subjects', InstructorSubjectController::class);
Route::apiResource('courses', CourseController::class);
Route::apiResource('instructor-courses', InstructorCourseController::class);
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
});
