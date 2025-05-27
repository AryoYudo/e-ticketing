<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StartController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\BuyController;

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

Route::get('/start', [StartController::class, 'start']);
Route::get('/events', [EventController::class, 'events'])->name('events');
Route::get('/detail', [DetailController::class, 'detail'])->name('detail');
Route::get('/buy', [BuyController::class, 'buy'])->name('buy');