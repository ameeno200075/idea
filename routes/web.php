<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\IdeaController;

Route::redirect('/', '/ideas');

Route::get('/ideas', [IdeaController::class, 'index'])->name('ideas.index')->middleware('auth');
Route::get('/ideas/{idea}', [IdeaController::class, 'show'])->name('ideas.show')->middleware('auth');

Route::get('/register', [RegisterController::class, 'create'])
    ->middleware('guest');

Route::post('/register', [RegisterController::class, 'store'])
    ->middleware('guest');

Route::get('/login', [SessionController::class, 'create'])->name('login')
    ->middleware('guest');

Route::post('/login', [SessionController::class, 'store'])->name('login.store')
    ->middleware('guest');

Route::post('/logout', [SessionController::class, 'destroy'])
    ->middleware('auth');
