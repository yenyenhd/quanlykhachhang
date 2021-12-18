<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;





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
// Home
Route::get('/', [HomeController::class,'index'])->name('home');
Route::post('/', [HomeController::class, 'login'])->name('admin.login');
Route::get('/', [HomeController::class, 'logout'])->name('admin.logout');
Route::get('404', [HomeController::class, 'error'])->name('login');

