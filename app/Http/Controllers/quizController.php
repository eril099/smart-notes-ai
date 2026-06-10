<?php

namespace App\Http\Controllers;
use App\Ai\Agents\QuizAgent;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(){
        $notes = session('notes', []);
        return view('quiz.index', [
            'notes' => $notes
        ]);
    }

    public function show($id){
        $notes = session('notes', []);
        $note = collect($notes)->firstWhere('id', (int) $id);

        if(!$note){
            abort(404);
        }

        $quiz = collect($note['quizzes'])->last();

        return view('quiz.show', [
            'notes' => $notes,
            'quiz' => $quiz
        ]);
    }

    public function generate($id){
        $id = (int) $id;
        $notes = session('notes', []);
        $note = collect($notes)->firstWhere('id', $id);

        if(!$note){
            abort(404);
        }
    
        $content = $note['summary'] ?: $note['content'];
        // Strip tags if it's HTML from markdown
        $content = strip_tags($content);

        $quiz = QuizAgent::make()->prompt($content);

        // Ensure quiz is not empty or malformed
        if (!$quiz || !isset($quiz['question'])) {
            return back()->with('error', 'Gagal menghasilkan quiz. Silakan coba lagi.');
        }

        $notes = collect($notes)
            ->map(function ($item) use ($id, $quiz) {
                if($item['id'] === $id){
                    $item['quizzes'][] = $quiz;
                }
                return $item;
            })
            ->all();

        session(['notes' => $notes]);

        return redirect("/quiz/{$id}");
    }
}
