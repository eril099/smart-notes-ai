<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use App\Ai\Agents\SummaryAgent;
use App\Models\Note;
use Illuminate\Http\Request;
use Laravel\Ai\Files;
use Laravel\Ai\Streaming\Events\TextDelta;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::where('username', session('username'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('notes.index', [
            'notes' => $notes
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Note::create([
            'username' => session('username'),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect('/notes');
    }

    public function show($id){
        $note = Note::where('username', session('username'))
            ->with('quizzes')
            ->findOrFail($id);

        return view('notes.show', [
            'note' => $note
        ]);
    }

    public function streamSummary($id)
    {
        $note = Note::where('username', session('username'))->findOrFail($id);

        $prompt = "Buat ringkasan yang jelas, singkat, dan mudah dipahami dari catatan berikut.";
        $attachments = [];

        if (!empty($note->document)) {
            $path = storage_path('/app/private/' . $note->document);
            $attachments[] = Files\Document::fromPath($path);
        } else {
            $prompt .= "\n\nContent: " . $note->content;
        }

        try {
            $stream = SummaryAgent::make()->stream($prompt, attachments: $attachments);

            return response()->stream(function () use ($stream, $note) {
                $fullSummary = "";
                try {
                    foreach ($stream as $chunk) {
                        if ($chunk instanceof TextDelta) {
                            $text = is_string($chunk->delta) ? $chunk->delta : (string) $chunk->delta;
                            $fullSummary .= $text;
                            echo "data: " . json_encode(['text' => $text], JSON_UNESCAPED_UNICODE) . "\n\n";
                            if (ob_get_level() > 0) {
                                ob_flush();
                            }
                            flush();
                        }
                    }

                    // Save the full summary to database
                    $note->update([
                        'summary' => Str::markdown($fullSummary)
                    ]);

                    echo "data: [DONE]\n\n";
                } catch (\Exception $e) {
                    $message = $e instanceof \Laravel\Ai\Exceptions\RateLimitedException 
                        ? "Limit AI tercapai. Silakan tunggu beberapa saat." 
                        : "Gagal memproses AI. Silakan coba lagi.";
                    
                    echo "data: " . json_encode(['error' => $message]) . "\n\n";
                }
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
            }, 200, [
                'Cache-Control' => 'no-cache',
                'Content-Type' => 'text/event-stream',
            ]);
        } catch (\Exception $e) {
             return response()->json(['error' => 'Gagal memulai koneksi AI.'], 500);
        }
    }

    public function summary($id){
        $note = Note::where('username', session('username'))->findOrFail($id);

        try {
            if(!empty($note->document)){
                $path = storage_path('/app/private/' . $note->document);
                $file = Files\Document::fromPath($path);

                $response = SummaryAgent::make()->prompt(
                    'Buat ringkasan yang jelas, singkat, dan mudah dipahami.',
                    attachments:[$file]
                );
            } else{
                $response = SummaryAgent::make()->prompt(
                    "Buat ringkasan dari catatan berikut.\n\nContent: " . $note->content
                );
            }

            // Ensure we get clean text from the response
            $summaryText = is_string($response) ? $response : (string) $response;

            $note->update([
                'summary' => Str::markdown($summaryText)
            ]);
        } catch (\Exception $e) {
            return redirect("/notes/{$id}")->with('error', 'Gagal meringkas. Silakan coba lagi.');
        }

        return redirect("/notes/{$id}");
    }

    public function upload(Request $request){
        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,txt|max:10240',
        ]);

        $path = $request->file('document')->store('documents');

        Note::create([
            'username' => session('username'),
            'title' => $request->file('document')->getClientOriginalName(),
            'content' => 'Dokumen: ' . $request->file('document')->getClientOriginalName(),
            'document' => $path,
        ]);

        return redirect('/notes');
    }

    public function destroy($id){
        $note = Note::where('username', session('username'))->findOrFail($id);
        $note->delete();

        return redirect('/notes');
    }
}
