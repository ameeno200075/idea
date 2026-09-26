<?php

use App\Http\Controllers\IdeaController;
use App\Http\Controllers\IdeaImageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StepController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/ideas');

Route::get('/ideas', [IdeaController::class, 'index'])->name('ideas.index')->middleware('auth');
Route::get('/ideas/{idea}', [IdeaController::class, 'show'])
    ->name('ideas.show')
    ->middleware('auth');

Route::delete('/ideas/{idea}', [IdeaController::class, 'destroy'])->name('ideas.destroy')->middleware('auth');

Route::post('/ideas', [IdeaController::class, 'store'])->name('ideas.store')->middleware('auth');
Route::get('/ideas/{idea}/edit', [IdeaController::class, 'edit'])->name('ideas.edit')->middleware('auth');
Route::patch('/ideas/{idea}', [IdeaController::class, 'update'])
    ->name('ideas.update')
    ->middleware('auth');

Route::delete('/ideas/{idea}/image', [IdeaImageController::class, 'destroy'])->name('ideas.destroy-image')->middleware('auth');

Route::patch('/steps/{step}', [StepController::class, 'update'])
    ->name('steps.update')
    ->middleware('auth');

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

Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('auth');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');
