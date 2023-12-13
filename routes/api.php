<?php

use App\Http\Controllers\Api\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\TestimonyController;


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

Route::post('/admin/login/{role}', 'AuthController@login')->where('role', 'superadmin|admin|member');

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

    Route::get('schools/{identifier}', [SchoolController::class, 'show'])
        ->where('identifier', '[0-9]+|[a-z0-9-]+');
    Route::get('schools', [SchoolController::class, 'index']);
    Route::post('/schools', [SchoolController::class, 'store']);
    
    Route::post('testimonies', [TestimonyController::class, 'store']);

});
