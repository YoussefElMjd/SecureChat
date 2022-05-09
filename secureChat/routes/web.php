<?php

use Illuminate\Support\Facades\Route;

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
    return view('auth/login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\ChatsController::class, 'index'])->name('home');
Route::post('/chat/store', [App\Http\Controllers\ChatsController::class, 'store']);
Route::get('/chat/{userRecipient_id}/messages', [App\Http\Controllers\ChatsController::class, 'getMessages']);

// Route::get('/chat',[App\Http\Controllers\ChatsController::class, 'fetchMessages']);
// Route::post('/chat',[App\Http\Controllers\ChatsController::class, 'sendMessage']);

