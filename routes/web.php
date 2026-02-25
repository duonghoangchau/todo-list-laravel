<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TodoController;

// Trang chủ → redirect về admin login
Route::get('/', fn() => redirect()->route('admin.login'));

// ─── Admin Auth (không cần đăng nhập) ────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout',[AuthController::class, 'logout'])->name('logout');
});

// ─── Admin Panel (cần đăng nhập + là admin) ───────────────
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Quản lý Users
    Route::get('/users',                    [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}',             [UserController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}/toggle',    [UserController::class, 'toggleActive'])->name('users.toggle');
    Route::patch('/users/{user}/role',      [UserController::class, 'changeRole'])->name('users.role');
    Route::delete('/users/{user}',          [UserController::class, 'destroy'])->name('users.destroy');

    // Quản lý Todos
    Route::get('/todos',             [TodoController::class, 'index'])->name('todos.index');
    Route::delete('/todos/{todo}',   [TodoController::class, 'destroy'])->name('todos.destroy');
});