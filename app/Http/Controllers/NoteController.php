<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use App\Ai\Agents\SummaryAgent;
use Illuminate\Http\Request;
use Illuminate\Mail\Attachment;
use Laravel\Ai\Files;

class NoteController extends Controller
{
    public function index()
    {
        $notes = session('notes', []);
        
        // Filter out existing empty notes (if any)
        $notes = array_filter($notes, function($note) {
            return !empty($note['title']) && (!empty($note['content']) || !empty($note['document']));
        });

        return view('notes.index', [
            'notes' => $notes
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $notes = session('notes', []);

        $notes[] = [
            'id' => count($notes) + 1,
            'title' => $request->title,
            'content' => $request->content,
            // 'document' => $path,
            'summary' => null,
            'quizzes' => [],
        ];

        session([
            'notes' => $notes
        ]);

        return redirect('/notes');
    }
    public function show($id){
        $notes = session('notes', []);
        $note = collect($notes)->firstWhere('id', (int) $id);

        if(!$note){
            abort(404);
        }
        return view('notes.show', [
            'note' => $note
        ]);
    }
    public function streamSummary($id)
    {
        $notes = session('notes', []);
        $note = collect($notes)->firstWhere('id', (int) $id);

        if (!$note) {
            abort(404);
        }

        $prompt = "Buat ringkasan yang jelas, singkat, dan mudah dipahami dari catatan berikut.";
        $attachments = [];

        if (!empty($note['document'])) {
            $path = storage_path('/app/private/' . $note['document']);
            $attachments[] = Files\Document::fromPath($path);
        } else {
            $prompt .= "\n\nContent: " . $note['content'];
        }

        try {
            $stream = SummaryAgent::make()->stream($prompt, attachments: $attachments);

            return response()->stream(function () use ($stream, $id) {
                $fullSummary = "";
                try {
                    foreach ($stream as $chunk) {
                        $fullSummary .= $chunk;
                        echo "data: " . json_encode(['text' => $chunk]) . "\n\n";
                        ob_flush();
                        flush();
                    }

                    // Save the full summary to session once finished
                    $notes = session('notes', []);
                    $notes = collect($notes)->map(function ($item) use ($id, $fullSummary) {
                        if ($item['id'] == $id) {
                            $item['summary'] = Str::markdown($fullSummary);
                        }
                        return $item;
                    })->all();
                    session(['notes' => $notes]);

                    echo "data: [DONE]\n\n";
                } catch (\Exception $e) {
                    $message = $e instanceof \Laravel\Ai\Exceptions\RateLimitedException 
                        ? "Limit AI tercapai. Silakan tunggu beberapa saat." 
                        : "Gagal memproses AI. Silakan coba lagi.";
                    
                    echo "data: " . json_encode(['error' => $message]) . "\n\n";
                }
                ob_flush();
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
        $notes = session('notes', []);
        $note = collect($notes)->firstWhere('id', (int) $id);

        if(!$note){
            abort(404);
        }

       if(!empty($note['document'])){
        $path = storage_path('/app/private/' . $note['document']);
        $file = Files\Document::fromPath($path);

        $summary = SummaryAgent::make()->prompt(
            'Buat ringkasan yang jelas, singkat, dan mudah dipahami.',
            attachments:[$file]
        );
       } else{
        $summary = SummaryAgent::make()->prompt(
            "Buat ringkasan dari catatan berikut.",
            $note['content']
        );
       }

       $notes = collect($notes)
       ->map(function ($item) use ($id, $summary){
        if($item['id'] == $id){
            $item['summary'] = Str::markdown(
                (string) $summary
            );
        }

        return $item;
       })
       ->all();

    session([
        'notes' => $notes
    ]);
    return redirect("/notes/{$id}");
    }
    public function upload(Request $request){
        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,txt|max:10240',
        ]);

        $path = $request->file('document')->store('documents');
        $notes = session('notes', []);
        $notes[] = [
            'id' => count($notes) + 1,
            'title' => $request->file('document')->getClientOriginalName(),
            'content' => 'Dokumen: ' . $request->file('document')->getClientOriginalName(),
            'document' => $path,
            'summary' => null,
            'quizzes' => [],
        ];

        session([
            'notes' => $notes
        ]);

        return redirect('/notes');
    }
}
