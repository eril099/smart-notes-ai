<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Quiz;
use App\Models\ChatConversation;

class DashboardController extends Controller
{
    public function index()
    {
        $username = session('username');

        $totalNotes = Note::where('username', $username)->count();
        $totalSummaries = Note::where('username', $username)->whereNotNull('summary')->count();
        $totalQuizzes = Quiz::whereHas('note', function ($query) use ($username) {
            $query->where('username', $username);
        })->count();
        $totalChats = ChatConversation::where('username', $username)->count();

        return view('Dashboard', [
            'totalNotes' => $totalNotes,
            'totalSummaries' => $totalSummaries,
            'totalQuizzes' => $totalQuizzes,
            'totalChats' => $totalChats,
        ]);
    }
}
