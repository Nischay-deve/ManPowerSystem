<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\GxonController;
use Illuminate\Support\Facades\Route;

// ================= AUTH =================

// Login page

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', [GxonController::class, 'loginBasic'])
    ->middleware('guest')
    ->name('login');

// Login submit
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest')
    ->name('login.submit');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Register page
Route::get('/register-basic', [GxonController::class, 'registerBasic'])
    ->middleware('guest')
    ->name('register-basic');

Route::middleware('auth')->group(function () {
    Route::get('/employees', [EmployeeController::class, 'index'])
        ->name('employees.index');

    Route::get('/employees/create', [EmployeeController::class, 'create'])
        ->name('employees.create');

    Route::post('/employees', [EmployeeController::class, 'store'])
        ->name('employees.store');

    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])
        ->name('employees.edit');

    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])
        ->name('employees.update');

    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
});


Route::middleware('guest')->group(function () {

    Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])
        ->name('forgot-password-basic');

    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
        ->name('forgot-password.submit');
});

// Register submit
Route::post('/register', [AuthController::class, 'register'])
    ->middleware('guest')
    ->name('register.submit');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [GxonController::class, 'index'])->name('index');

    // Route::get('employee', [GxonController::class, 'employee'])->name('employee');
    Route::get('attendance', [GxonController::class, 'attendance'])->name('attendance');
    Route::get('leave', [GxonController::class, 'leave'])->name('leave');
    Route::get('payroll', [GxonController::class, 'payroll'])->name('payroll');
    Route::get('recruitment', [GxonController::class, 'recruitment'])->name('recruitment');
    Route::get('task-management', [GxonController::class, 'taskManagement'])->name('task-management');
    Route::get('analytics', [GxonController::class, 'analytics'])->name('analytics');
    Route::get('chat', [GxonController::class, 'chat'])->name('chat');
    Route::get('profile', [GxonController::class, 'profile'])->name('profile');
    Route::get('calendar', [GxonController::class, 'calendar'])->name('calendar');
    Route::get('inbox', [GxonController::class, 'emailInbox'])->name('inbox');
});


Route::middleware('auth')->group(function () {
    Route::resource('departments', DepartmentController::class);
    Route::resource('designations', DesignationController::class);
});
