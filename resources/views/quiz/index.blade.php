@extends('layouts.app')

@section('content')
<div class="space-y-4">
<h2 class="text-3xl font-bold dark:text-white">
    Quiz Yok
</h2>

<p class="text-slate-500 mt-2">
    ayo bikin quiz
</p>

<div class="mt-8 space-y-4">
    @forelse($notes as $note)
    @php
        $latestQuiz = $note->latestQuiz;
        $questionCount = 0;
        if ($latestQuiz && isset($latestQuiz->questions['question'])) {
            $questionCount = count($latestQuiz->questions['question']);
        }
    @endphp
    <x-card>
        <h3 class="font-bold dark:text-white">{{ $note->title }}</h3>
        <p class="text-slate-500">
            {{ $questionCount }} Soal
        </p>
         <div class="flex gap-2 mt-4">
            <a href="/quiz/{{ $note->id }}">
                 <x-button>
                    Masuk ke Quiz
                </x-button>
            </a>
        </div>
    </x-card>
    @empty
    <p class="dark:text-slate-400">Belum ada quiz yang dibuat. Silakan buka catatan Anda dan pilih "Quiz AI".</p>
    @endforelse
</div>

</div>
@endsection
