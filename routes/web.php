<?php

use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\Auth\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// admin routes
Route::group(['prefix' => 'admin','middleware' => ['auth', 'isAdmin']], function() {

    Route::get('dashboard', [AuthController::class, 'adminDashboard'])->name('admin.dashboard'); 

});

// client registration
Route::get('registration', [AuthController::class, 'registration'])->name('register');

// admin registration
Route::get('admin/registration', [AuthController::class, 'adminRegistration'])->name('admin.register');

// common routes
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post'); 
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post'); 

// client routes
Route::middleware(['auth', 'isClient'])->group(function () {
    Route::get('clientProfile', [AuthController::class, 'clientProfile']); 
});
