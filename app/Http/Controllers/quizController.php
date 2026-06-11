<?php

namespace App\Http\Controllers;
use App\Ai\Agents\QuizAgent;
use App\Models\Note;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(){
        $notes = Note::where('username', session('username'))
            ->has('quizzes')
            ->with('latestQuiz')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('quiz.index', [
            'notes' => $notes
        ]);
    }

    public function show($id){
        $note = Note::where('username', session('username'))
            ->with('quizzes')
            ->findOrFail($id);

        $quiz = $note->quizzes->last();

        return view('quiz.show', [
            'note' => $note,
            'quiz' => $quiz
        ]);
    }

    public function generate($id){
        $id = (int) $id;
        $note = Note::where('username', session('username'))->findOrFail($id);

        $content = $note->summary ?: $note->content;
        // Strip tags if it's HTML from markdown
        $content = strip_tags($content);

        try {
            $response = QuizAgent::make()->prompt($content);

            // Convert structured response to plain array
            $quiz = null;

            if (is_object($response) && method_exists($response, 'toArray')) {
                $quiz = $response->toArray();
            } elseif (is_array($response)) {
                $quiz = $response;
            } else {
                // Try to decode if it's a JSON string
                $decoded = json_decode((string) $response, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $quiz = $decoded;
                }
            }

            // Handle different possible response structures
            if ($quiz && !isset($quiz['question'])) {
                if (isset($quiz[0]) && isset($quiz[0]['question'])) {
                    $quiz = ['question' => $quiz];
                }
                elseif (isset($quiz['data']) && is_array($quiz['data'])) {
                    $quiz = ['question' => $quiz['data']];
                }
                elseif (isset($quiz['questions']) && is_array($quiz['questions'])) {
                    $quiz = ['question' => $quiz['questions']];
                }
            }

            // Ensure quiz is not empty or malformed
            if (!$quiz || !isset($quiz['question']) || empty($quiz['question'])) {
                return back()->with('error', 'Gagal menghasilkan quiz. Format respons tidak valid. Silakan coba lagi.');
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghasilkan quiz. Silakan coba lagi. Error: ' . $e->getMessage());
        }

        // Save quiz to database
        Quiz::create([
            'note_id' => $note->id,
            'questions' => $quiz,
        ]);

        return redirect("/quiz/{$id}");
    }
}
