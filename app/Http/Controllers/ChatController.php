<?php

namespace App\Http\Controllers;

use App\Ai\Agents\ChatAgent;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Streaming\Events\TextDelta;

class ChatController extends Controller
{
    /**
     * Tampilkan daftar percakapan.
     */
    public function index()
    {
        $conversations = ChatConversation::where('username', session('username'))
            ->with('latestMessage')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('chat.index', [
            'conversations' => $conversations
        ]);
    }

    /**
     * Buat percakapan baru.
     */
    public function store()
    {
        $conversation = ChatConversation::create([
            'username' => session('username'),
            'title' => 'Percakapan Baru',
        ]);

        return redirect("/chat/{$conversation->id}");
    }

    /**
     * Tampilkan percakapan dengan histori pesan.
     */
    public function show($id)
    {
        $conversation = ChatConversation::where('username', session('username'))
            ->with(['messages' => function ($query) {
                $query->orderBy('created_at', 'asc');
            }])
            ->findOrFail($id);

        $conversations = ChatConversation::where('username', session('username'))
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('chat.show', [
            'conversation' => $conversation,
            'conversations' => $conversations,
        ]);
    }

    /**
     * Kirim pesan dan stream respons AI.
     */
    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $conversation = ChatConversation::where('username', session('username'))
            ->findOrFail($id);

        // Simpan pesan user ke database
        ChatMessage::create([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $request->message,
        ]);

        // Update judul percakapan dari pesan pertama
        if ($conversation->messages()->count() <= 1) {
            $conversation->update([
                'title' => Str::limit($request->message, 50),
            ]);
        }

        // Update timestamp percakapan
        $conversation->touch();

        return response()->json(['status' => 'ok']);
    }

    /**
     * Stream respons AI untuk percakapan.
     */
    public function streamResponse($id)
    {
        $conversation = ChatConversation::where('username', session('username'))
            ->with(['messages' => function ($query) {
                $query->orderBy('created_at', 'asc');
            }])
            ->findOrFail($id);

        // Bangun histori pesan untuk AI
        $messages = [];
        foreach ($conversation->messages as $msg) {
            $messages[] = new Message($msg->role, $msg->content);
        }

        // Ambil pesan terakhir user sebagai prompt
        $lastUserMessage = $conversation->messages->where('role', 'user')->last();
        if (!$lastUserMessage) {
            return response()->json(['error' => 'Tidak ada pesan untuk diproses.'], 400);
        }

        // Hapus pesan terakhir dari histori (karena akan jadi prompt)
        array_pop($messages);

        try {
            $agent = ChatAgent::make()->withMessages($messages);
            $stream = $agent->stream($lastUserMessage->content);

            return response()->stream(function () use ($stream, $conversation) {
                $fullResponse = "";
                try {
                    foreach ($stream as $chunk) {
                        if ($chunk instanceof TextDelta) {
                            $text = is_string($chunk->delta) ? $chunk->delta : (string) $chunk->delta;
                            $fullResponse .= $text;
                            echo "data: " . json_encode(['text' => $text], JSON_UNESCAPED_UNICODE) . "\n\n";
                            if (ob_get_level() > 0) {
                                ob_flush();
                            }
                            flush();
                        }
                    }

                    // Simpan respons AI ke database
                    ChatMessage::create([
                        'conversation_id' => $conversation->id,
                        'role' => 'assistant',
                        'content' => $fullResponse,
                    ]);

                    echo "data: [DONE]\n\n";
                } catch (\Exception $e) {
                    $message = $e instanceof \Laravel\Ai\Exceptions\RateLimitedException
                        ? "Limit AI tercapai. Silakan tunggu beberapa saat."
                        : "Gagal memproses AI. Silakan coba lagi.";

                    echo "data: " . json_encode(['error' => $message], JSON_UNESCAPED_UNICODE) . "\n\n";
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

    /**
     * Hapus percakapan.
     */
    public function destroy($id)
    {
        $conversation = ChatConversation::where('username', session('username'))
            ->findOrFail($id);

        $conversation->delete();

        return redirect('/chat');
    }
}
