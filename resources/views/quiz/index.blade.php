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
    @php
        $notesWithQuizzes = array_filter($notes, function($note) {
            return !empty($note['quizzes']);
        });
    @endphp

    @forelse($notesWithQuizzes as $note)
    @php
        $lastQuiz = collect($note['quizzes'])->last();
        $questionCount = isset($lastQuiz['question']) ? count($lastQuiz['question']) : 0;
    @endphp
    <x-card>
        <h3 class="font-bold dark:text-white">{{ $note['title'] }}</h3>
        <p class="text-slate-500">
            {{ $questionCount }} Soal
        </p>
         <div class="flex gap-2 mt-4">
            <a href="/quiz/{{ $note['id'] }}">
                 <x-button>
                    Masuk ke Quiz
                </x-button>
            </a>
               
                <x-button class="bg-red-600">
                    Hapus
                </x-button>
            </div>
    </x-card>
    @empty
    <p class="dark:text-slate-400">Belum ada quiz yang dibuat. Silakan buka catatan Anda dan pilih "Quiz AI".</p>
    @endforelse
</div>

</div>
@endsection
