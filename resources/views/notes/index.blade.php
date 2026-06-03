@extends('layouts.app')

@section('content')
<div class="space-y-4">
<h2 class="text-3xl font-bold dark:text-white">
    Notes
</h2>

<p class="dark:text-slate-400 mt-2">
    Buat Catatanmu
</p>

<x-card>
    <h3 class="text-lg font-bold mb-8 dark:text-white">Tambahkan Catatan</h3>

    <div class="space-y-4">
        <div class="mb-4">
                <label class="block mb-2 dark:text-slate-400">Judul</label>
                <input type="text" name="tittle" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        <div class="mb-4">
                <label class="block mb-2 dark:text-slate-400">Isi catatan</label>
                <textarea rows="5" type="text" name="username" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
    </div>
    <x-button>Simpan</x-button>
</x-card>

<x-card>
    <h3 class="lg font-semibold dark:text-white">Upload Catatan</h3>
    <input type="file" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
    <p class="text-slate 500 mt-4 dark:text-slate-400">Upload catatan yang ingin anda upload</p>
</x-card>

<div>
    <h3 class="text-xl font-bold mb-4 dark:text-white">Catatan saya</h3>
    <div class="space-y-4">
        <x-card>
            <h4 class="text-xl font-bold dark:text-white">Belajar Laravel</h4>
            <p class="text-shadow-yellow-50 mt-2 dark:text-slate-400">materi Laravel ada.......</p>
            <div class="flex gap-2 mt-4">
                <x-button>
                    Simpulkan AI
                </x-button>
                <x-button class="bg-emerald-600">
                    Quiz AI
                </x-button>
                <x-button class="bg-red-600">
                    Hapus
                </x-button>
            </div>
        </x-card>
         <x-card>
            <h4 class="text-xl font-bold dark:text-white">belajar Node JS</h4>
            <p class="text-shadow-yellow-50 mt-2 dark:text-slate-400">NPM</p>
                <div class="flex gap-2 mt-4">
                <x-button>
                    Simpulkan AI
                </x-button>
                <x-button class="bg-emerald-600">
                    Quiz AI
                </x-button>
                <x-button class="bg-red-600">
                    Hapus
                </x-button>
            </div>
        </x-card>
    </div>
</div>

</div>


@endsection