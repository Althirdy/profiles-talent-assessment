<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    return $request->session()->has('user_id')
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

// Guest Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Protected Routes
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/employees', [DashboardController::class, 'store'])->name('employees.store');
Route::put('/employees/{id}', [DashboardController::class, 'update'])->name('employees.update');
Route::delete('/employees/{id}', [DashboardController::class, 'destroy'])->name('employees.destroy');
