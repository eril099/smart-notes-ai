@extends('layouts.app')

@section('content')
<div class="space-y-4">
<h2 class="text-3xl font-bold dark:text-white">
    Pertanyaan
</h2>

@if($quiz && isset($quiz['question']))
    @foreach($quiz['question'] as $index => $item)
    <x-card>
        <h3 class="font-semibold mb-4 dark:text-white">Soal {{ $index + 1 }}</h3>
        <p class="mb-4 dark:text-slate-400">{{ $item['question'] }}</p>

        <div class="space-y-2 dark:text-slate-400">
            <div>A. {{ $item['option_a'] }}</div>
            <div>B. {{ $item['option_b'] }}</div>
            <div>C. {{ $item['option_c'] }}</div>
            <div>D. {{ $item['option_d'] }}</div>
        </div>
    </x-card>
    @endforeach
@else
    <x-card>
        <p class="dark:text-white text-center py-8">Belum ada quiz yang dihasilkan untuk catatan ini.</p>
        <div class="flex justify-center mt-4">
            <a href="/notes/{{ request()->route('id') }}/quiz">
                <x-button>Hasilkan Quiz Sekarang</x-button>
            </a>
        </div>
    </x-card>
@endif

</div>
@endsection
