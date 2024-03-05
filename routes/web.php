<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/test', function () {
        return Inertia::render('Test');
    })->name('test');
    Route::resource('pupil', \App\Http\Controllers\PupilController::class);
    Route::resource('learning_material', \App\Http\Controllers\LearningMaterialController::class);
    Route::resource('lesson', \App\Http\Controllers\LessonController::class);
    Route::resource('calendar', \App\Http\Controllers\CalendarController::class);
});
