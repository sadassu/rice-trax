<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductBatchController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::inertia('/about', 'About')->name('about');


Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('/product-batches', ProductBatchController::class);
    Route::get('/product-batches/batch/{product}', [ProductBatchController::class, 'showBatchesByProduct'])->name('product-batches.product.show');
    Route::resource('/products', ProductController::class)->except(['create']);

    Route::get('/admin-dashboard', [DashboardController::class, 'index'])->name('admin.dashboard')->middleware('role:admin');
    Route::inertia('/register', 'Auth/Register')->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/attendances/create', [AttendanceController::class, 'create'])->name('attendances.create');
    Route::post('/attendances', [AttendanceController::class, 'store'])->name('attendances.store');
    Route::resource('/activity-logs', ActivityLogController::class)->only(['index', 'delete']);
    Route::resource('/attendances', AttendanceController::class)->except(['create', 'store'])->middleware('role:admin');
    Route::resource('/employees', EmployeeController::class)->except(['index', 'show']);
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos', [PosController::class, 'checkout'])->name('checkout');
    Route::get('/receipt/{sale}', [PosController::class, 'receipt'])->name('receipt');
    Route::get('/accounts', [AuthController::class, 'accounts'])->name('accounts')->middleware('role:admin');
    Route::resource('/sales', SaleController::class)->except(['create', 'update', 'destroy'])->middleware('role:admin');
    Route::post('/accounts/change-password', [AuthController::class, 'changePassword'])
        ->name('accounts.change-password')->middleware('role:admin');
});


Route::middleware('guest')->group(function () {
    Route::inertia('/login', 'Auth/Login')->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
