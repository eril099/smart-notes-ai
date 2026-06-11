@extends('layouts.app')

@section('content')
<div class="flex flex-col" style="height: calc(100vh - 120px);">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <a href="/chat" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h2 class="text-xl font-bold dark:text-white" id="chat-title">{{ $conversation->title }}</h2>
        </div>
        <form method="POST" action="/chat/{{ $conversation->id }}" onsubmit="return confirm('Hapus percakapan ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-500 hover:text-red-700 p-1" title="Hapus Percakapan">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
            </button>
        </form>
    </div>

    {{-- Messages Container --}}
    <div id="messages-container" class="flex-1 overflow-y-auto space-y-4 pb-4 pr-2 scroll-smooth">
        @forelse($conversation->messages as $message)
            @if($message->role === 'user')
            <div class="flex justify-end">
                <div class="bg-indigo-600 text-white rounded-2xl rounded-br-md px-4 py-3 max-w-[75%] shadow">
                    <p class="whitespace-pre-wrap">{{ $message->content }}</p>
                </div>
            </div>
            @else
            <div class="flex justify-start">
                <div class="bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-2xl rounded-bl-md px-4 py-3 max-w-[75%] shadow">
                    <div class="prose dark:prose-invert max-w-none text-sm">
                        {!! \Illuminate\Support\Str::markdown($message->content) !!}
                    </div>
                </div>
            </div>
            @endif
        @empty
        <div class="flex items-center justify-center h-full" id="empty-state">
            <div class="text-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mx-auto text-slate-300 dark:text-slate-600 mb-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                </svg>
                <p class="text-slate-400 dark:text-slate-500 text-lg">Mulai percakapan dengan mengetik pesan di bawah</p>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Input Area --}}
    <div class="mt-4 border-t dark:border-slate-700 pt-4">
        <form id="chat-form" class="flex gap-3">
            <input
                type="text"
                id="message-input"
                placeholder="Ketik pesan Anda..."
                autocomplete="off"
                class="flex-1 px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-slate-800 dark:text-white text-sm"
            >
            <button
                type="submit"
                id="send-btn"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl transition-colors flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                </svg>
                <span id="send-text">Kirim</span>
            </button>
        </form>
    </div>
</div>

<script>
const conversationId = {{ $conversation->id }};
const messagesContainer = document.getElementById('messages-container');
const chatForm = document.getElementById('chat-form');
const messageInput = document.getElementById('message-input');
const sendBtn = document.getElementById('send-btn');
const sendText = document.getElementById('send-text');

function scrollToBottom() {
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// Scroll ke bawah saat pertama kali load
scrollToBottom();

function addUserMessage(text) {
    // Hapus empty state jika ada
    const emptyState = document.getElementById('empty-state');
    if (emptyState) emptyState.remove();

    const div = document.createElement('div');
    div.className = 'flex justify-end';
    div.innerHTML = `
        <div class="bg-indigo-600 text-white rounded-2xl rounded-br-md px-4 py-3 max-w-[75%] shadow">
            <p class="whitespace-pre-wrap">${escapeHtml(text)}</p>
        </div>
    `;
    messagesContainer.appendChild(div);
    scrollToBottom();
}

function createAssistantBubble() {
    const div = document.createElement('div');
    div.className = 'flex justify-start';
    div.innerHTML = `
        <div class="bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-2xl rounded-bl-md px-4 py-3 max-w-[75%] shadow">
            <div class="prose dark:prose-invert max-w-none text-sm" id="ai-response">
                <p class="animate-pulse text-slate-400">Sedang berpikir...</p>
            </div>
        </div>
    `;
    messagesContainer.appendChild(div);
    scrollToBottom();
    return div.querySelector('#ai-response');
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function setLoading(loading) {
    sendBtn.disabled = loading;
    messageInput.disabled = loading;
    sendText.textContent = loading ? 'Mengirim...' : 'Kirim';
}

chatForm.addEventListener('submit', async function(e) {
    e.preventDefault();

    const message = messageInput.value.trim();
    if (!message) return;

    messageInput.value = '';
    setLoading(true);

    // Tampilkan pesan user
    addUserMessage(message);

    try {
        // Kirim pesan ke server
        const response = await fetch(`/chat/${conversationId}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ message: message })
        });

        if (!response.ok) {
            throw new Error('Gagal mengirim pesan');
        }

        // Buat bubble AI dan mulai streaming
        const responseBubble = createAssistantBubble();
        let fullText = '';

        const eventSource = new EventSource(`/chat/${conversationId}/stream`);

        eventSource.onmessage = function(event) {
            if (event.data === '[DONE]') {
                eventSource.close();
                setLoading(false);
                messageInput.focus();
                return;
            }

            try {
                const data = JSON.parse(event.data);

                if (data.error) {
                    eventSource.close();
                    responseBubble.innerHTML = `<p class="text-red-500">${data.error}</p>`;
                    setLoading(false);
                    messageInput.focus();
                    return;
                }

                fullText += data.text;
                responseBubble.innerHTML = marked.parse(fullText);
                scrollToBottom();
            } catch (err) {
                console.error('Error parsing stream:', err);
            }
        };

        eventSource.onerror = function(err) {
            console.error('EventSource error:', err);
            eventSource.close();
            if (!fullText) {
                responseBubble.innerHTML = '<p class="text-red-500">Terjadi kesalahan. Silakan coba lagi.</p>';
            }
            setLoading(false);
            messageInput.focus();
        };

    } catch (error) {
        console.error('Error:', error);
        const errorBubble = createAssistantBubble();
        errorBubble.innerHTML = '<p class="text-red-500">Gagal mengirim pesan. Silakan coba lagi.</p>';
        setLoading(false);
        messageInput.focus();
    }
});

// Focus input saat halaman load
messageInput.focus();
</script>

<style>
    #messages-container::-webkit-scrollbar {
        width: 6px;
    }
    #messages-container::-webkit-scrollbar-track {
        background: transparent;
    }
    #messages-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    .dark #messages-container::-webkit-scrollbar-thumb {
        background: #475569;
    }
    #messages-container .prose pre {
        background: #1e293b;
        border-radius: 8px;
        padding: 12px;
        overflow-x: auto;
    }
    #messages-container .prose code {
        font-size: 0.85em;
    }
</style>
@endsection
