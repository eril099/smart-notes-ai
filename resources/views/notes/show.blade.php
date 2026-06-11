@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <a href="/notes" class="text-indigo-600 dark:text-indigo-400">
            Kembali
        </a>
    </div>
    <x-card>
        <h2 class="text-xl font-bold text-slate-900 dark:text-white">
            {{ $note->title }}
        </h2>

        @if (!empty($note->content) && !$note->document)
        <p class="text-slate-600 dark:text-slate-300 leading-relaxed mt-4">
            {{ $note->content }}
        </p>
        @elseif($note->document)
            <div>
                <p class="mt-4 text-slate-500 dark:text-slate-400">
                    Dokumen Berhasil diunggah
                </p>
                <p class="text-sm text-slate-400 dark:text-slate-50">
                    {{ basename($note->document) }}
                </p>
            </div>
        @endif
    </x-card>

    @if(session('error'))
    <x-card>
        <p class="text-red-500 font-semibold">{{ session('error') }}</p>
    </x-card>
    @endif

    <x-card>
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">
            Fitur AI
        </h3>

        <div class="flex gap-2">
            @if(!$note->summary)
            <x-button id="btn-summary" onclick="startSummary()">
                Ringkas AI
            </x-button>
            @endif

            <a href="/notes/{{ $note->id }}/quiz">
                <x-button class="bg-emerald-600 dark:bg-emerald-500">
                    Quiz AI
                </x-button>
            </a>
        </div>
    </x-card>

    <div id="summary-container" @if(!$note->summary) class="hidden" @endif>
        <x-card>
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                Ringkasan AI
            </h3>
            <div id="summary-content" class="text-slate-600 dark:text-slate-300 leading-relaxed prose dark:prose-invert max-w-none">
                {!! $note->summary !!}
            </div>
        </x-card>
    </div>
</div>

<script>
    function startSummary() {
        const btn = document.getElementById('btn-summary');
        const container = document.getElementById('summary-container');
        const content = document.getElementById('summary-content');

        btn.disabled = true;
        btn.innerText = 'Sedang Meringkas...';
        container.classList.remove('hidden');
        content.innerHTML = '<p class="animate-pulse">Menghubungkan ke AI...</p>';

        let fullText = '';
        const eventSource = new EventSource('/notes/{{ $note->id }}/summary/stream');

        eventSource.onmessage = function(event) {
            if (event.data === '[DONE]') {
                eventSource.close();
                btn.remove();
                return;
            }

            try {
                const data = JSON.parse(event.data);
                
                if (data.error) {
                    eventSource.close();
                    content.innerHTML = `<p class="text-red-500">${data.error}</p>`;
                    btn.disabled = false;
                    btn.innerText = 'Ringkas AI';
                    return;
                }

                fullText += data.text;
                content.innerHTML = marked.parse(fullText);
            } catch (e) {
                console.error('Error parsing stream data', e);
            }
        };

        eventSource.onerror = function(err) {
            console.error('EventSource failed:', err);
            eventSource.close();
            content.innerHTML = '<p class="text-red-500">Terjadi kesalahan saat meringkas. Silakan muat ulang halaman.</p>';
            btn.disabled = false;
            btn.innerText = 'Ringkas AI';
        };
    }
</script>

@endsection