<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Manager\TaskController as ManagerTaskController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;
use App\Http\Controllers\Manager\PerformanceReviewController as ManagerReviewController;
use App\Http\Controllers\Manager\DailyWorkUpdateController as ManagerWorkUpdateController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Employee\TaskController as EmployeeTaskController;
use App\Http\Controllers\Employee\DailyWorkUpdateController as EmployeeWorkUpdateController;
use App\Http\Controllers\Employee\PerformanceReviewController as EmployeeReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route(auth()->check() ? 'dashboard' : 'login');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.store');
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.store');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard redirect based on role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manager routes
    Route::middleware('can:manage-tasks')->prefix('manager')->name('manager.')->group(function () {
        Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');

        Route::get('/tasks', [ManagerTaskController::class, 'index'])->name('tasks.index');
        Route::get('/tasks/create', [ManagerTaskController::class, 'create'])->name('tasks.create');
        Route::post('/tasks', [ManagerTaskController::class, 'store'])->name('tasks.store');
        Route::get('/tasks/{task}', [ManagerTaskController::class, 'show'])->name('tasks.show');
        Route::get('/tasks/{task}/edit', [ManagerTaskController::class, 'edit'])->name('tasks.edit');
        Route::put('/tasks/{task}', [ManagerTaskController::class, 'update'])->name('tasks.update');
        Route::delete('/tasks/{task}', [ManagerTaskController::class, 'destroy'])->name('tasks.destroy');

        Route::get('/reviews', [ManagerReviewController::class, 'index'])->name('reviews.index');
        Route::get('/reviews/create', [ManagerReviewController::class, 'create'])->name('reviews.create');
        Route::post('/reviews', [ManagerReviewController::class, 'store'])->name('reviews.store');
        Route::get('/reviews/{review}', [ManagerReviewController::class, 'show'])->name('reviews.show');
        Route::get('/reviews/{review}/edit', [ManagerReviewController::class, 'edit'])->name('reviews.edit');
        Route::put('/reviews/{review}', [ManagerReviewController::class, 'update'])->name('reviews.update');
        Route::delete('/reviews/{review}', [ManagerReviewController::class, 'destroy'])->name('reviews.destroy');

        Route::get('/work-updates', [ManagerWorkUpdateController::class, 'index'])->name('work-updates.index');
    });

    // Employee routes
    Route::middleware('can:employee-access')->prefix('employee')->name('employee.')->group(function () {
        Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');

        Route::get('/tasks', [EmployeeTaskController::class, 'index'])->name('tasks.index');
        Route::get('/tasks/{task}', [EmployeeTaskController::class, 'show'])->name('tasks.show');
        Route::patch('/tasks/{task}/status', [EmployeeTaskController::class, 'updateStatus'])->name('tasks.update-status');

        Route::get('/work-updates', [EmployeeWorkUpdateController::class, 'index'])->name('work-updates.index');
        Route::get('/work-updates/create', [EmployeeWorkUpdateController::class, 'create'])->name('work-updates.create');
        Route::post('/work-updates', [EmployeeWorkUpdateController::class, 'store'])->name('work-updates.store');
        Route::get('/work-updates/{workUpdate}', [EmployeeWorkUpdateController::class, 'show'])->name('work-updates.show');

        Route::get('/reviews', [EmployeeReviewController::class, 'index'])->name('reviews.index');
        Route::get('/reviews/{review}', [EmployeeReviewController::class, 'show'])->name('reviews.show');
    });
});