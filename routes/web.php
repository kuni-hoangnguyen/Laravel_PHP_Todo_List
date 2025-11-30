<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', [HomeController::class, 'index'])
    ->middleware('auth.check')
    ->name('dashboard');

// CRUD Task
Route::middleware('auth.check')->group(function () {
    Route::post('/tasks', [HomeController::class, 'store'])->name('tasks.store');              // Tạo mới
    Route::put('/tasks/{task}', [HomeController::class, 'update'])->name('tasks.update');      // Cập nhật
    Route::delete('/tasks/{task}', [HomeController::class, 'destroy'])->name('tasks.destroy'); // Xóa
});
Route::post('/tasks/{id}/complete', [HomeController::class, 'toggleComplete'])->name('tasks.complete');

// Auth routes
Route::get('register', [AuthController::class, 'showRegistrationForm'])
    ->middleware('guest.check')
    ->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register.post');

Route::get('login', [AuthController::class, 'showLoginForm'])
    ->middleware('guest.check')
    ->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');

Route::get('logout', [AuthController::class, 'logout'])->name('logout');

// Reports routes
Route::get('/reports', [ReportsController::class, 'index'])
    ->middleware('auth.check')
    ->name('reports');

// Profile routes
Route::middleware('auth.check')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});