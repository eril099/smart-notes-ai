<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\testController;
use App\Http\Middleware\checkLogin;
use AWS\CRT\HTTP\Request;

use function Laravel\Ai\{agent};
use Illuminate\Support\Str;
// use Illuminate\Http\Request;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', [DashboardController::class, 'index'])->middleware(checkLogin::class)->name('dashboard');
Route::get('/notes', [NoteController::class, 'index'])->middleware(checkLogin::class)->name('notes.index');
Route::post('/notes', [NoteController::class, 'store'])->middleware(checkLogin::class)->name('notes.store');
Route::get('/notes/{id}', [NoteController::class, 'show'])->middleware(checkLogin::class)->name('notes.show');
Route::post('/notes/{id}/summary', [NoteController::class, 'summary'])->middleware(checkLogin::class)->name('notes.summary');
Route::get('/notes/{id}/summary/stream', [NoteController::class, 'streamSummary'])->middleware(checkLogin::class)->name('notes.summary.stream');
Route::post('/notes/upload', [NoteController::class, 'upload'])->middleware(checkLogin::class)->name('notes.upload');
Route::get('/notes/{id}/quiz', [QuizController::class, 'generate'])->middleware(checkLogin::class)->name('notes.quiz');


Route::get('/quiz', [QuizController::class, 'index'])->middleware(checkLogin::class)->name('quiz.index');
Route::get('/quiz/{id}', [QuizController::class, 'show'])->middleware(checkLogin::class)->name('quiz.show');

// Route::get('/playground-ai', function(Request $request) {
//     $prompt = $request->prompt;
//          return view('playground-ai');
//     if($prompt){    
//     }
//     $response = agent(
//         instructions:'Kamu adalah seorang mentor Laravel yang membantu saya belajar pemrograman laravel
//         berikan jawaban yang singkat, jelas, dan mudah dipahami.'
//     )->prompt($prompt);

//     $html = Str::markdown(
//         (string) $response
//     );

//     return view('playground-ai', ['response' => $html]);

// });