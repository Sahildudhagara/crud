<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DeshbordController;
use App\Http\Controllers\CategoryController;

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

Route::get('/', function () {
    return view('register');
});

Route::get('/login',[LoginController::class,'index'])->name('login');
Route::post('/authenticate',[LoginController::class,'authenticate'])->name('authenticate');
Route::get('/deshbord',[DeshbordController::class,'index'])->name('deshbord');

Route::get('/register',[LoginController::class,'register'])->name('register');
Route::post('/process-register',[LoginController::class,'processRegister'])->name('processRegister');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//Route::resource('category', CategoryController::class)->middleware('auth');

Route::middleware(['auth'])->group(function () {
    // This will create all the necessary routes for categories
    Route::resource('category', CategoryController::class);
});




