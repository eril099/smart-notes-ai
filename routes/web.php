<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ChatController;
use App\Http\Middleware\checkLogin;

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->middleware(checkLogin::class)->name('dashboard');

// Notes
Route::get('/notes', [NoteController::class, 'index'])->middleware(checkLogin::class)->name('notes.index');
Route::post('/notes', [NoteController::class, 'store'])->middleware(checkLogin::class)->name('notes.store');
Route::get('/notes/{id}', [NoteController::class, 'show'])->middleware(checkLogin::class)->name('notes.show');
Route::delete('/notes/{id}', [NoteController::class, 'destroy'])->middleware(checkLogin::class)->name('notes.destroy');
Route::post('/notes/{id}/summary', [NoteController::class, 'summary'])->middleware(checkLogin::class)->name('notes.summary');
Route::get('/notes/{id}/summary/stream', [NoteController::class, 'streamSummary'])->middleware(checkLogin::class)->name('notes.summary.stream');
Route::post('/notes/upload', [NoteController::class, 'upload'])->middleware(checkLogin::class)->name('notes.upload');

// Quiz
Route::get('/notes/{id}/quiz', [QuizController::class, 'generate'])->middleware(checkLogin::class)->name('notes.quiz');
Route::get('/quiz', [QuizController::class, 'index'])->middleware(checkLogin::class)->name('quiz.index');
Route::get('/quiz/{id}', [QuizController::class, 'show'])->middleware(checkLogin::class)->name('quiz.show');

// Chat AI
Route::get('/chat', [ChatController::class, 'index'])->middleware(checkLogin::class)->name('chat.index');
Route::post('/chat', [ChatController::class, 'store'])->middleware(checkLogin::class)->name('chat.store');
Route::get('/chat/{id}', [ChatController::class, 'show'])->middleware(checkLogin::class)->name('chat.show');
Route::delete('/chat/{id}', [ChatController::class, 'destroy'])->middleware(checkLogin::class)->name('chat.destroy');
Route::post('/chat/{id}/send', [ChatController::class, 'sendMessage'])->middleware(checkLogin::class)->name('chat.send');
Route::get('/chat/{id}/stream', [ChatController::class, 'streamResponse'])->middleware(checkLogin::class)->name('chat.stream');