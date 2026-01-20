<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Bank\BankAccountController;

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\EmployeeController as ControllersEmployeeController;
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

    Route::get('/employees', [ControllersEmployeeController::class, 'index'])->name('employees.index');

    Route::get('/employees/create', [ControllersEmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [ControllersEmployeeController::class, 'store'])->name('employees.store');

    Route::get('/employees/{employee}/edit', [ControllersEmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [ControllersEmployeeController::class, 'update'])->name('employees.update');

    Route::delete('/employees/{employee}', [ControllersEmployeeController::class, 'destroy'])->name('employees.destroy');
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

    // Route::get('/dashboard', [GxonController::class, 'index'])->name('index');

    // Route::get('employee', [GxonController::class, 'employee'])->name('employee');
    // Route::get('attendance', [GxonController::class, 'attendance'])->name('attendance');
    Route::get('leave', [GxonController::class, 'leave'])->name('leave');
    Route::get('payroll', [GxonController::class, 'payroll'])->name('payroll');
    Route::get('recruitment', [GxonController::class, 'recruitment'])->name('recruitment');
    Route::get('task-management', [GxonController::class, 'taskManagement'])->name('task-management');
    Route::get('analytics', [GxonController::class, 'analytics'])->name('analytics');
    Route::get('chat', [GxonController::class, 'chat'])->name('chat');
    // Route::get('profile', [GxonController::class, 'profile'])->name('profile');
    Route::get('calendar', [GxonController::class, 'calendar'])->name('calendar');
    Route::get('inbox', [GxonController::class, 'emailInbox'])->name('inbox');
});


Route::middleware('auth')->group(function () {
    // Route::resource('departments', DepartmentController::class);
    Route::resource('designations', DesignationController::class);
});


Route::middleware('auth')->group(function () {
    Route::get('/bank-accounts', [BankAccountController::class, 'index'])->name('bank.index');
    Route::get('/bank-accounts/create', [BankAccountController::class, 'create'])->name('bank.create');
    Route::post('/bank-accounts', [BankAccountController::class, 'store'])->name('bank.store');

    Route::get('/bank-accounts/{bank}/edit', [BankAccountController::class, 'edit'])->name('bank.edit');
    Route::put('/bank-accounts/{bank}', [BankAccountController::class, 'update'])->name('bank.update');

    Route::delete('/bank-accounts/{bank}', [BankAccountController::class, 'destroy'])->name('bank.destroy');
});


Route::middleware('auth')->group(function () {
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');

    Route::patch('/documents/{document}/deactivate', [DocumentController::class, 'deactivate'])->name('documents.deactivate');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
});


use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('index');
});

// routes/web.php
Route::get('/profile', [ProfileController::class, 'show'])
    ->name('profile')
    ->middleware('auth');

Route::post('/profile/password', [ProfileController::class, 'updatePassword'])
    ->name('profile.password.update')
    ->middleware('auth');
