<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StartController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\BuyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AddEventController;

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


Route::get('/select-event', [EventController::class, 'events'])->name('events');
Route::get('/detail/{id}', [DetailController::class, 'detail'])->name('detail');
Route::get('/buy/{id}', [BuyController::class, 'buy'])->name('buy');
Route::post('/order_request/{ticket_id}', [BuyController::class, 'orderRequest'])->name('orderRequest');

// {NOTIFICATION}
Route::post('/midtrans/notification', [MidtransController::class, 'handleNotification']);

// {evets admin}
// {auth admin}
Route::get('/auth', [AuthController::class, 'auth'])->name('auth');
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');

// Group route yang butuh login
Route::middleware(['admin.auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/listEvents', [DashboardController::class, 'listEvents'])->name('listEvents');
    Route::get('/showEventTabel', [AddEventController::class, 'showEventTabel'])->name('showEventTabel');
    Route::post('/addEvent', [AddEventController::class, 'addEvent'])->name('addEvent');
    Route::delete('/destroy/{id}', [AddEventController::class, 'destroy'])->name('destroy');
    Route::put('/editEvent/{id}', [AddEventController::class, 'editEvent'])->name('editEvent');
});

