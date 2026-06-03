<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\quizController;
use App\Http\Controllers\testController;
use App\Http\Middleware\checkLogin;



Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', [DashboardController::class, 'index'])->middleware(checkLogin::class)->name('dashboard');
Route::get('/notes', [NoteController::class, 'index'])->middleware(checkLogin::class)->name('notes.index');
Route::get('/quiz', [quizController::class, 'index'])->middleware(checkLogin::class)->name('notes.index');
Route::get('/show-quiz', [quizController::class, 'show'])->middleware(checkLogin::class)->name('notes.index');