<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [RegisterUserController::class, 'create'])->name('register');
Route::post('/register', [RegisterUserController::class, 'store'])->name('register.store');
Route::get('/login', [SessionController::class, 'create'])->name('login');
Route::post('/login', [SessionController::class, 'store'])->name('login.store');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [SessionController::class, 'destroy'])->name('logout');
    Route::get('/home', [UserController::class, 'home'])->name('users.home');
    Route::get('/profile', [UserController::class, 'profile'])->name('users.profile');
    Route::patch('/profile-update', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::post('/password/update', [UserController::class, 'updatePassword'])->name('password.update');
    Route::get('/stats', [UserController::class, 'stats'])->name('users.stats');

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
        Route::patch('/{user}/assign-role', [UserController::class, 'assign_role'])->name('users.assign_role');
        Route::delete('/{user}/delete', [UserController::class, 'delete'])->name('users.destroy');
    });

    Route::prefix('departments')->group(function () {
        Route::get('/', [DepartmentController::class, 'index'])->name('departments.index');
        Route::get('/create', [DepartmentController::class, 'create'])->name('departments.create');
        Route::post('/store', [DepartmentController::class, 'store'])->name('departments.store');
        Route::get('/{department}', [DepartmentController::class, 'show'])->name('departments.show');
        Route::patch('/{department}/update', [DepartmentController::class, 'update'])->name('departments.update');
        Route::delete('/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
    });

    Route::prefix('events')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('events.index');
        Route::get('/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/store', [EventController::class, 'store'])->name('events.store');
        Route::get('/{event}', [EventController::class, 'show'])->name('events.show');
        Route::patch('/{event}/update', [EventController::class, 'update'])->name('events.update');
        Route::post('/{event}/register', [EventController::class, 'register'])->name('events.register');
        Route::delete('/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    });
});
