@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <div>
        <a href="/notes/{{ $note->id }}" class="text-indigo-600 dark:text-indigo-400">
            Kembali ke Catatan
        </a>
    </div>

<h2 class="text-3xl font-bold dark:text-white">
    Pertanyaan - {{ $note->title }}
</h2>

@if(session('error'))
<x-card>
    <p class="text-red-500 font-semibold">{{ session('error') }}</p>
    <div class="flex mt-4">
        <a href="/notes/{{ $note->id }}/quiz">
            <x-button>Coba Lagi</x-button>
        </a>
    </div>
</x-card>
@endif

@if($quiz && isset($quiz->questions['question']) && is_array($quiz->questions['question']) && count($quiz->questions['question']) > 0)
    @foreach($quiz->questions['question'] as $index => $item)
    <x-card>
        <h3 class="font-semibold mb-4 dark:text-white">Soal {{ $index + 1 }}</h3>
        <p class="mb-4 dark:text-slate-300">{{ $item['question'] ?? 'Pertanyaan tidak tersedia' }}</p>

        <div class="space-y-2 dark:text-slate-400">
            <div>A. {{ $item['option_a'] ?? '-' }}</div>
            <div>B. {{ $item['option_b'] ?? '-' }}</div>
            <div>C. {{ $item['option_c'] ?? '-' }}</div>
            <div>D. {{ $item['option_d'] ?? '-' }}</div>
        </div>

        @if(isset($item['answer']))
        <p class="mt-4 text-sm font-semibold text-emerald-600 dark:text-emerald-400">
            Jawaban: {{ $item['answer'] }}
        </p>
        @endif
    </x-card>
    @endforeach
@else
    <x-card>
        <p class="dark:text-white text-center py-8">Belum ada quiz yang dihasilkan untuk catatan ini.</p>
        <div class="flex justify-center mt-4">
            <a href="/notes/{{ $note->id }}/quiz">
                <x-button>Hasilkan Quiz Sekarang</x-button>
            </a>
        </div>
    </x-card>
@endif

</div>
@endsection
