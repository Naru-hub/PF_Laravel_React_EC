<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/**
 * 管理者側ルーティング
 */
Route::prefix('admin')
    ->group(function () {
        Route::get('/login', function () {
            // 管理者のログインページを表示
            return Inertia::render('Admin/Login');
        })->name('admin.login');

        // 管理者のログイン処理
        Route::post('/login', [AdminController::class, 'login']);

        // 管理者のログアウト処理
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    });

// 管理者用の認証保護されたルート
Route::middleware('auth:admin')
    ->prefix('admin')
    ->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Admin/Dashboard');
        })->name('admin.dashboard');
    });

// ログインしていないユーザーを/admin/loginにリダイレクト
Route::get('/admin', fn() => redirect(route('admin.login')));

require __DIR__ . '/auth.php';
