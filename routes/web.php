<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Redirect root to students index
Route::get('/', function () {
    return redirect()->route('students.index');
});

// Student Resource Routes
Route::resource('students', StudentController::class);