<?php

use App\Http\Controllers\Api\SchoolController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('schools')->group(function() {
    Route::get('/', [SchoolController::class, 'index']);
    Route::get('/{identifier}', [SchoolController::class, 'show'])
        ->where('identifier', '[0-9]+|[a-z0-9-]+');
});