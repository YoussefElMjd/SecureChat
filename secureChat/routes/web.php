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
use App\Http\Controllers\ChatsController;
Route::get('/home', [ChatsController::class, 'index'])->name('home');
Route::post('/chat/store', [ChatsController::class, 'store']);
Route::get('/chat/{userRecipient_id}/messages', [ChatsController::class, 'getMessages']);
Route::get('/chat/members', [ChatsController::class, 'getAllMembers']);
Route::get('/chat/{userRecipient_id}/invite',[ChatsController::class, 'addInvitation']);
Route::get('/chat/invitation',[ChatsController::class, 'getInvitation']);
Route::get('/chat/contactFriends',[ChatsController::class, 'getContactFriends']);
Route::get('/chat/invitation/{userRecipient_id}/accept',[ChatsController::class, 'acceptInvitation']);
Route::get('/chat/invitation/{userRecipient_id}/denied',[ChatsController::class, 'deniedInvitation']);
Route::get('/chat/contactFriends/{userRecipient_id}/remove',[ChatsController::class, 'removeContact']);
Route::get('/chat/contacts',[ChatsController::class, 'getAllContacts']);
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');


// Route::get('/chat',[App\Http\Controllers\ChatsController::class, 'fetchMessages']);
// Route::post('/chat',[App\Http\Controllers\ChatsController::class, 'sendMessage']);

