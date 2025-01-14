<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\BoardsController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Auth\PasswordProtectionController;

Auth::routes();

Route::get('oauth/login', [\App\Http\Controllers\Auth\LoginController::class, 'redirectToProvider'])
     ->middleware('guest')
     ->name('oauth.login');
Route::get('oauth/callback', [\App\Http\Controllers\Auth\LoginController::class, 'handleProviderCallback'])->middleware('guest');

Route::get('password-protection', PasswordProtectionController::class)->name('password.protection');
Route::post('password-protection', [PasswordProtectionController::class, 'login'])->name('password.protection.login');

Route::get('/', \App\Http\Controllers\HomeController::class)->name('home');

Route::get('projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('items/{item}', [ItemController::class, 'show'])->name('items.show');
Route::get('projects/{project}/items/{item}', [ItemController::class, 'show'])->name('projects.items.show');
Route::post('projects/{project}/items/{item}/vote', [ItemController::class, 'vote'])->middleware('auth')->name('projects.items.vote');
Route::get('projects/{project}/boards/{board}', [BoardsController::class, 'show'])->name('projects.boards.show');

Route::group(['middleware' => 'auth'], function () {
    Route::get('profile', [\App\Http\Controllers\Auth\ProfileController::class, 'show'])->name('profile');
    Route::get('my', MyController::class)->name('my');

    Route::get('mention-search', \App\Http\Controllers\MentionSearchController::class)->name('mention-search');
    Route::get('user/{username}', \App\Http\Controllers\PublicUserController::class)->name('public-user');
});
