<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StartController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\BuyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

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

Route::get('/', [StartController::class, 'start']);

// {auth admin}
Route::get('/auth', [AuthController::class, 'auth'])->name('auth');
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');

// {evets admin}
Route::get('/events', [EventController::class, 'events'])->name('events');
Route::get('/listEvents', [EventController::class, 'listEvents'])->name('listEvents');

Route::get('/detail', [DetailController::class, 'detail'])->name('detail');
Route::get('/buy', [BuyController::class, 'buy'])->name('buy');
Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
