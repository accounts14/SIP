<?php

use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\ProvinceController;
use App\Http\Controllers\Admin\RegencyController;
use App\Http\Controllers\Admin\SubdistrictController;
use App\Http\Controllers\Api\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\TestimonyController;
use App\Http\Controllers\Api\RegistrationFormController;
use App\Http\Controllers\Api\StudentRegistrationController;
use App\Http\Controllers\Api\UserCandidateController;

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

Route::post('register', RegisterController::class);

Route::post('/admin/login/{role}', 'AuthController@login')->where('role', 'superadmin|admin|member')->name('login');

Route::middleware(['auth:api'])->prefix('api')->group(function () {
    // Web 
    Route::get('products', 'WebProductController@index');
    // location
    // id only : /api/locations/{type}?id=1
    // name only : /api/locations/{type}?name=YourName
    // id & name : /api/locations/{type}?id=1&name=YourName
    Route::get('locations/{type}', 'LocationsController@getByType')
        ->where('type', 'province|city|district|subdistrict')
        ->name('locations.get');

    Route::prefix('schools')->group(function() {
        Route::get('/', [SchoolController::class, 'index']);
        Route::get('/{identifier}', [SchoolController::class, 'show'])
            ->where('identifier', '[0-9]+|[a-z0-9-]+');
        Route::post('/', [SchoolController::class, 'store']);
        Route::put('/{id}', [SchoolController::class, 'update']);
        Route::delete('/{id}', [SchoolController::class, 'destroy']);
    });
    Route::get('get-nearest-schools', [SchoolController::class, 'getNearestSchools']);
    
    Route::post('testimonies', [TestimonyController::class, 'store']);

    Route::apiResource('provinces', ProvinceController::class);
    Route::apiResource('regencies', RegencyController::class);
    Route::apiResource('cities', CityController::class);
    Route::apiResource('districts', DistrictController::class);
    Route::apiResource('subdistricts', SubdistrictController::class);
    
    Route::apiResource('registration', StudentRegistrationController::class);
    Route::apiResource('student', UserCandidateController::class);
    Route::apiResource('registration-form', RegistrationFormController::class);
    Route::get('registration-form/sch/{sch_id}', [RegistrationFormController::class, 'fromSchool']);
});
