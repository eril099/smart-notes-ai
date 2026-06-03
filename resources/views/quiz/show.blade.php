@extends('layouts.app')

@section('content')
<div class="space-y-4">
<h2 class="text-3xl font-bold dark:text-white">
    Pertanyaan
</h2>

<x-card>
    <h3 class="font-semibold mb-4 dark:text-white">Soal 1</h3>
    <p class="mb-4 dark:text-slate-400">Apa itu composer</p>

    <div class="space-y-2 dark:text-slate-400">
        <div>A. Sebuah Package manager untuk php</div>
        <div>B. Bawaan aplikasi Node JS</div>
        <div>C. Package Manager Node JS</div>
        <div>D. Sebuah Package manager untuk php</div>
    </div>
</x-card>

</div>
@endsection