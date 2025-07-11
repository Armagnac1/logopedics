<?php

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
Route::get('calendar/generate_calendar/{user}', [\App\Http\Controllers\Web\CalendarController::class, 'generateCalendar']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'impersonate',
])->group(function () {
    Route::redirect('/', '/calendar')->name('home');
    Route::get('/test', function () {
        return Inertia::render('Test');
    })->name('test');
    Route::resource('pupil', \App\Http\Controllers\Web\PupilController::class);
    Route::resource('media', \App\Http\Controllers\Web\MediaController::class)->parameters(['media' => 'media']);
    Route::resource('learning_material', \App\Http\Controllers\Web\LearningMaterialController::class);
    Route::resource('tags', \App\Http\Controllers\Web\TagController::class);
    Route::resource('lesson', \App\Http\Controllers\Web\LessonController::class);
    Route::get('lesson/create/{pupil}', [\App\Http\Controllers\Web\LessonController::class, 'create'])->name('lesson.create');
    Route::resource('lesson_learning_material', \App\Http\Controllers\Web\LessonLearningMaterialController::class);
    Route::resource('calendar', \App\Http\Controllers\Web\CalendarController::class);
    Route::resource('city', \App\Http\Controllers\Web\CityController::class);
    Route::get('search', [\App\Http\Controllers\Web\SearchController::class, 'index'])->name('search');
    Route::name('admin.')->prefix('admin')->controller(\App\Http\Controllers\Web\AdminController::class)->group(function () {
        Route::get('/', 'index')->name('index')->middleware(['can:login as others']);
        Route::get('login-as/{user}', 'loginAs')->name('login-as')->middleware(['can:login as others']);
        Route::get('impersonate-back', 'impersonateBack')->name('impersonate-back');
    });
    Route::get('logout', [\Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
