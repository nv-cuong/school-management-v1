<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'authLogin']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
Route::post('change-password', [AuthController::class, 'changePassword'])->name('change-password');
Route::post('reset/{token}', [AuthController::class, 'reset'])->name('reset');
Route::post('reset/{token}', [AuthController::class, 'postReset'])->name('postReset');

Route::get('admin/admin/list', function () {
    return view('admin.admin.list');
});

Route::group(['middleware' => 'admin'], function () {

    Route::get('admin/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

});

Route::group(['middleware' => 'teacher'], function () {
    Route::get('teacher/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

});

Route::group(['middleware' => 'student'], function () {
    Route::get('student/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

});

Route::group(['middleware' => 'parent'], function () {
    Route::get('parent/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

});