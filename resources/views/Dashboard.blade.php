@extends('layouts.app')

@section('content')
<h2 class="text-3xl font-bold mb-2 dark:text-white">
    Selamat Datang
</h2>

<p class="text-slate-600 dark:text-slate-400 mb-8">
    Siap belajar?
</p>

<div class="grid md:grid-cols-3 gap-6">
    <x-card>
        <p class="text-slate-500 dark:text-slate-400">
            Total catatan
        </p>
        <h3 class="text-3xl font-bold mt-2 dark:text-white">
            12
        </h3>
    </x-card>

    <x-card>
        <p class="text-slate-500 dark:text-slate-400">
            Total catatan
        </p>
        <h3 class="text-3xl font-bold mt-2 dark:text-white">
            12
        </h3>
    </x-card>

    <x-card>
        <p class="text-slate-500 dark:text-slate-400">
            Total catatan
        </p>
        <h3 class="text-3xl font-bold mt-2 dark:text-white">
            12
        </h3>
    </x-card>
</div>
@endsection