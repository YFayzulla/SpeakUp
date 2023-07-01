<?php

use App\Http\Controllers\ExtraController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserCantroller;
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

Route::get('/',function (){return view('welcome');});

// admin panel
Route::middleware('auth' , 'role:admin|manager' )->group(function () {
    Route::resource('/dashboard', UserCantroller::class)->middleware(['auth', 'verified']);
    Route::resource('/extra', ExtraController::class)->middleware(['auth', 'verified']);
    Route::resource('/student', StudentController::class)->middleware(['auth', 'verified']);
    Route::resource('/group', GroupController::class)->middleware(['auth', 'verified']);
});

// user
Route::middleware('auth',)->group(function () {
});
//profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile', [ProfileController::class, 'save'])->name('profile.save');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
