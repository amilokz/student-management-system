<?php
// routes/web.php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\ReportController; // ← ADD THIS LINE
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Protected routes (add auth middleware if using authentication)
Route::middleware(['web'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Student routes
    Route::resource('students', StudentController::class);
    Route::get('/students/{student}/profile', [StudentController::class, 'profile'])->name('students.profile');
    Route::get('/export/students/excel', [StudentController::class, 'exportExcel'])->name('students.export.excel');
    Route::get('/export/students/pdf', [StudentController::class, 'exportPDF'])->name('students.export.pdf');
    Route::get('/students/{student}/id-card', [StudentController::class, 'generateIdCard'])->name('students.id-card');
    
    // Course routes
    Route::resource('courses', CourseController::class);
    Route::get('/courses/{course}/students', [CourseController::class, 'students'])->name('courses.students');
    
    // Attendance routes
    Route::resource('attendance', AttendanceController::class);
    Route::post('/attendance/mark-bulk', [AttendanceController::class, 'markBulk'])->name('attendance.mark-bulk');
    Route::get('/attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');
    
    // Fee routes
    Route::resource('fees', FeeController::class);
    Route::get('/fees/{fee}/receipt', [FeeController::class, 'receipt'])->name('fees.receipt');
    Route::get('/students/{student}/fees', [FeeController::class, 'studentFees'])->name('students.fees');
    
    // Reports - NOW UNCOMMENT AND IT WILL WORK
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/attendance', [ReportController::class, 'attendance'])->name('attendance');
        Route::get('/fees', [ReportController::class, 'fees'])->name('fees');
        Route::get('/students', [ReportController::class, 'students'])->name('students');
    });
});

// Authentication routes (if using Breeze/Jetstream)
require __DIR__.'/auth.php';