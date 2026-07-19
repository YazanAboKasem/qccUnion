<?php

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', 'App\Http\Controllers\Web\AuthController@showLogin')->name('login');
    Route::post('/login', 'App\Http\Controllers\Web\AuthController@login');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', 'App\Http\Controllers\Web\AuthController@logout')->name('logout');

    Route::get('/', 'App\Http\Controllers\Web\DashboardController@index')->name('dashboard');
    Route::get('/dashboard', 'App\Http\Controllers\Web\DashboardController@index');

    // Surveys
    Route::get('/surveys/{survey}', 'App\Http\Controllers\Web\SurveyController@show')->name('surveys.show');
    Route::put('/surveys/{survey}', 'App\Http\Controllers\Web\SurveyController@update')->name('surveys.update');
    Route::post('/surveys/{survey}/duplicate', 'App\Http\Controllers\Web\SurveyController@duplicate')->name('surveys.duplicate');

    // Questions
    Route::post('/surveys/{survey}/questions', 'App\Http\Controllers\Web\QuestionController@store')->name('questions.store');
    Route::get('/questions/{question}/edit', 'App\Http\Controllers\Web\QuestionController@edit')->name('questions.edit');
    Route::put('/questions/{question}', 'App\Http\Controllers\Web\QuestionController@update')->name('questions.update');
    Route::delete('/questions/{question}', 'App\Http\Controllers\Web\QuestionController@destroy')->name('questions.destroy');
    Route::patch('/questions/{question}/toggle', 'App\Http\Controllers\Web\QuestionController@toggle')->name('questions.toggle');
    Route::post('/surveys/{survey}/questions/reorder', 'App\Http\Controllers\Web\QuestionController@reorder')->name('questions.reorder');

    // Reports
    Route::get('/surveys/{survey}/reports', 'App\Http\Controllers\Web\ReportController@show')->name('reports.show');
    Route::get('/surveys/{survey}/reports/export', 'App\Http\Controllers\Web\ReportController@export')->name('reports.export');

    // Pledges
    Route::get('/pledges', 'App\Http\Controllers\Web\PledgeController@index')->name('pledges.index');
    Route::get('/pledges/export', 'App\Http\Controllers\Web\PledgeController@export')->name('pledges.export');
    Route::get('/pledges/{pledge}', 'App\Http\Controllers\Web\PledgeController@show')->name('pledges.show');
    Route::delete('/pledges/{pledge}', 'App\Http\Controllers\Web\PledgeController@destroy')->name('pledges.destroy');
});

// Fallback image routing for environments without public storage symlinks (like Hostinger Shared Hosting)
Route::get('/storage/pledges/signatures/{filename}', function ($filename) {
    $path = 'pledges/signatures/' . $filename;
    if (!Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
        abort(404);
    }
    $file = Illuminate\Support\Facades\Storage::disk('public')->get($path);
    return response($file, 200)->header('Content-Type', 'image/png');
});
