<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupMemberController;
use App\Http\Controllers\GroupChatController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard',  [UserController::class, 'loadDash'])->middleware(['auth', 'verified'])->name('dashboard');
Route::post('/save-chat',  [ChatController::class, 'saveChat'])->middleware(['auth', 'verified'])->name('save-chat');
Route::post('/load-chat',  [ChatController::class, 'loadChat'])->middleware(['auth', 'verified'])->name('load-chat');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/save-group',  [GroupController::class, 'saveGroup'])->middleware(['auth', 'verified'])->name('save-group');
Route::get('/view-groups',  [GroupController::class, 'loadGroups'])->middleware(['auth', 'verified'])->name('view-groups');
Route::get('/view-members',  [GroupController::class, 'viewMembers'])->middleware(['auth', 'verified'])->name('view-members');
Route::get('/user-groups',  [GroupController::class, 'groupChats'])->middleware(['auth', 'verified'])->name('user-groups');

Route::post('/group-chat',  [GroupChatController::class, 'groupChat'])->middleware(['auth', 'verified'])->name('group-chat');
Route::post('/load-group-chat',  [GroupChatController::class, 'loadGroupChat'])->middleware(['auth', 'verified'])->name('load-group-chat');

Route::post('/add-members',  [GroupMemberController::class, 'addMembers'])->middleware(['auth', 'verified'])->name('add-members');

require __DIR__.'/auth.php';
