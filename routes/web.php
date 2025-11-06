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
use App\Models\Employee;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::inertia('/about', 'About')->name('about');


Route::middleware('auth')->group(function () {

    //account related routes
    Route::inertia('/register', 'Auth/Register')
        ->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
    Route::post('/accounts/change-password', [AuthController::class, 'changePassword'])
        ->name('accounts.change-password')
        ->middleware('role:admin');
    Route::get('/accounts', [AuthController::class, 'accounts'])
        ->name('accounts')
        ->middleware('role:admin');

    //others
    Route::get('/product-batches/batch/{product}', [ProductBatchController::class, 'showBatchesByProduct'])
        ->name('product-batches.product.show');
    Route::get('/admin-dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard')->middleware('role:admin');
    Route::get('/sales-report', [DashboardController::class, 'salesReport'])
        ->name('dashboard.sales-report');
    Route::post('/employees/{employee}/compute-salary', [EmployeeController::class, 'computeSalary'])
        ->name('employees.computeSalary');

    Route::get('/employee/{employee}/compute-salary', function (Employee $employee) {
        return Inertia::render('Employees/ComputeSalary', [
            'employee' => $employee,
        ]);
    })->name('employees.computeSalary.page');

    //export excel
    Route::post("/sales/export", [SaleController::class, 'export'])
        ->name("sales.export")
        ->middleware('role:admin');

    //import excel
    Route::post("/sales/import", [SaleController::class, 'import'])
        ->name("sales.import")
        ->middleware('role:admin');

    //attendances without the middleware of admin this can be accessed by the normal user
    Route::get('/attendances/create', [AttendanceController::class, 'create'])
        ->name('attendances.create');
    Route::post('/attendances', [AttendanceController::class, 'store'])
        ->name('attendances.store');

    //POINT OF SALE: routes
    Route::get('/pos', [PosController::class, 'index'])
        ->name('pos.index');
    Route::post('/pos', [PosController::class, 'checkout'])
        ->name('checkout');

    Route::get('/receipt/{sale}', [PosController::class, 'receipt'])
        ->name('receipt');

    // resources
    Route::resource('/product-batches', ProductBatchController::class);
    Route::resource('/activity-logs', ActivityLogController::class)
        ->only(['index', 'delete']);
    Route::resource('/products', ProductController::class)
        ->except(['create']);
    Route::resource('/employees', EmployeeController::class);
    Route::resource('/sales', SaleController::class)
        ->except(['create', 'update', 'destroy']);

    //resource for admin only
    Route::resource('/attendances', AttendanceController::class)
        ->except(['create', 'store'])
        ->middleware('role:admin');
});


Route::middleware('guest')->group(function () {
    Route::inertia('/login', 'Auth/Login')->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
