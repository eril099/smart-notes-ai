@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <h2 class="text-3xl font-bold dark:text-white">
        Notes
    </h2>

    <p class="dark:text-slate-400 mt-2">
        Buat Catatanmu
    </p>

    <form method="POST" action="/notes">
        @csrf
        <x-card>
            <h3 class="text-lg font-bold mb-8 dark:text-white">Tambahkan Catatan</h3>

            <div class="space-y-4">
                <div class="mb-4">
                    <label class="block mb-2 dark:text-slate-400">Judul</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                    @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block mb-2 dark:text-slate-400">Isi catatan</label>
                    <textarea rows="5" name="content" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                    @error('content')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <x-button>Simpan</x-button>
        </x-card>
    </form>
    <x-card>
        <h3 class="lg font-semibold dark:text-white">Upload Catatan</h3>
        <form method="POST" action="/notes/upload" enctype="multipart/form-data">
            @csrf
            <input type="file" name="document" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <x-button>Kirim</x-button>
        </form>
        <p class="text-slate 500 mt-4 dark:text-slate-400">Upload catatan yang ingin anda upload</p>
    </x-card>

    <div>
        <h3 class="text-xl font-bold mb-4 dark:text-white">Catatan saya</h3>
        <div class="space-y-4">
            @forelse($notes as $note)
            <x-card>
                <h4 class="text-xl font-bold dark:text-white">{{ $note->title }}</h4>
                <p class="text-shadow-yellow-50 mt-2 dark:text-slate-400">{{ Str::limit($note->content, 100) }}</p>
                <div class="flex gap-2 mt-4">
                    <a href="/notes/{{ $note->id }}">
                        <x-button>
                            Detail
                        </x-button>
                    </a>
                    <form method="POST" action="/notes/{{ $note->id }}" onsubmit="return confirm('Hapus catatan ini?')">
                        @csrf
                        @method('DELETE')
                        <x-button class="bg-red-600">
                            Hapus
                        </x-button>
                    </form>
                </div>
            </x-card>
            @empty
            <x-card>
                <p class="text-slate-500 dark:text-slate-400">
                    Belum ada Catatan yang dibuat.
                </p>
            </x-card>
            @endforelse

        </div>
    </div>

</div>


@endsection