<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\ProvinceController;
use App\Http\Controllers\Admin\RegencyController;
use App\Http\Controllers\Admin\SchoolLevelConroller;
use App\Http\Controllers\Admin\SubdistrictController;
use App\Http\Controllers\Api\AchievementController;
use App\Http\Controllers\Api\AgendaController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\ExtracurricularController;
use App\Http\Controllers\Api\FacilityController;
use App\Http\Controllers\Api\FacilityTypeController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\TestimonyController;
use App\Http\Controllers\Api\RegistrationFormController;
use App\Http\Controllers\Api\StudentRegistrationController;
use App\Http\Controllers\Api\UserCandidateController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserMemberController;
use App\Http\Controllers\Api\ExtracurricularTypeController;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

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

Route::post('login/{role}', 'AuthController@login')->where('role', 'superadmin|admin|member')->name('login');

Route::middleware(['auth:api'])->prefix('api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('me', function() {
        return ['user' => new UserResource(Auth::user())];
    });
    Route::get('products', 'WebProductController@index');
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
        Route::put('/generate-user/{id}', [SchoolController::class, 'genUser']);
    });
    
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
    Route::apiResource('school-levels', SchoolLevelConroller::class);

    Route::apiResource('messages', ChatController::class);
    Route::get('message-threads', [ChatController::class, 'getThreads']);

    Route::apiResource('teachers', TeacherController::class);
    
    Route::apiResource('users', UserController::class);
    Route::apiResource('user-member', UserMemberController::class);
    
    Route::apiResource('facility-type', FacilityTypeController::class);
    Route::post('facility/upload/{id}', [FacilityController::class, 'uploadImg']);
    Route::apiResource('facility', FacilityController::class);
    Route::apiResource('extracurricular', ExtracurricularController::class);
    Route::apiResource('extracurricular-type', ExtracurricularTypeController::class);
    Route::apiResource('achievement', AchievementController::class);
    Route::apiResource('gallery', GalleryController::class);
    Route::apiResource('news', BlogController::class);
});

// get data for member (mobile app)
Route::middleware(['auth:api'])->group(function () {
    // location
    // id only : /api/locations/{type}?id=1
    // name only : /api/locations/{type}?name=YourName
    // id & name : /api/locations/{type}?id=1&name=YourName
    Route::get('locations/{type}', 'LocationsController@getByType')
        ->where('type', 'province|city|district|subdistrict')
        ->name('locations.get');

    Route::prefix('schools')->group(function() {
        Route::get('/', [SchoolController::class, 'index']);
        Route::get('/facility/{schID}', [FacilityController::class, 'bySchool']);
        Route::get('/extracurricular/{schID}', [ExtracurricularController::class, 'bySchool']);
        Route::get('/achievement/{schID}', [AchievementController::class, 'bySchool']);
        Route::get('/gallery/{schID}', [GalleryController::class, 'bySchool']);
        Route::get('/teachers/{schID}', [TeacherController::class, 'bySchool']);
        Route::get('/news/{schID}', [BlogController::class, 'bySchool']);
        Route::get('/agenda/{schID}', [AgendaController::class, 'bySchool']);
        Route::get('/{identifier}', [SchoolController::class, 'show'])
            ->where('identifier', '[0-9]+|[a-z0-9-]+');
    });

    Route::get('get-nearest-schools-coord', [SchoolController::class, 'getNearestSchoolsByCoord']);
    Route::get('get-nearest-schools-location', [SchoolController::class, 'getNearestSchoolsByLocation']);
});

// without login

// Route::prefix('schools')->group(function() {
//     Route::get('/', [SchoolController::class, 'index']);
//     Route::get('/{identifier}', [SchoolController::class, 'show'])
//         ->where('identifier', '[0-9]+|[a-z0-9-]+');
//     Route::post('/', [SchoolController::class, 'store']);
//     Route::put('/{id}', [SchoolController::class, 'update']);
//     Route::delete('/{id}', [SchoolController::class, 'destroy']);
//     Route::put('/generate-user/{id}', [SchoolController::class, 'genUser']);
// });

// Route::apiResource('school-levels', SchoolLevelConroller::class);

// Route::get('locations/{type}', 'LocationsController@getByType')
// ->where('type', 'province|city|district|subdistrict')
// ->name('locations.get');