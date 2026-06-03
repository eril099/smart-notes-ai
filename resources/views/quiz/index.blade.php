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
    <x-card>
        <h3 class="font-bold dark:text-white">belajar Laravel</h3>
        <p class="text-slate-500">
            5 Soal
        </p>
         <div class="flex gap-2 mt-4">
            <a href="/show-quiz">
                 <x-button>
                    Masuk ke Quiz
                </x-button>
            </a>
               
                <x-button class="bg-red-600">
                    Hapus
                </x-button>
            </div>
    </x-card>
    <x-card>
        <h3 class="font-bold dark:text-white">belajar Node JS</h3>
        <p class="text-slate-500">
            5 Soal
        </p>
         <div class="flex gap-2 mt-4">
                <a href="/show-quiz">
                 <x-button>
                    Masuk ke Quiz
                </x-button>
            </a>
                <x-button class="bg-red-600">
                    Hapus
                </x-button>
            </div>
    </x-card>
</div>

</div>
@endsection