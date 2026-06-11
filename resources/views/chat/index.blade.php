@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold dark:text-white">
                Chat AI
            </h2>
            <p class="text-slate-500 mt-2 dark:text-slate-400">
                Tanya apa saja kepada AI
            </p>
        </div>
        <form method="POST" action="/chat">
            @csrf
            <x-button>
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Percakapan Baru
                </span>
            </x-button>
        </form>
    </div>

    <div class="mt-8 space-y-4">
        @forelse($conversations as $conversation)
        <x-card>
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <a href="/chat/{{ $conversation->id }}" class="block">
                        <h3 class="font-bold dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            {{ $conversation->title }}
                        </h3>
                        @if($conversation->latestMessage)
                        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
                            {{ Str::limit($conversation->latestMessage->content, 80) }}
                        </p>
                        @endif
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-2">
                            {{ $conversation->updated_at->diffForHumans() }}
                        </p>
                    </a>
                </div>
                <form method="POST" action="/chat/{{ $conversation->id }}" onsubmit="return confirm('Hapus percakapan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700 p-1 ml-4" title="Hapus">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </button>
                </form>
            </div>
        </x-card>
        @empty
        <x-card>
            <div class="text-center py-8">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 mx-auto text-slate-400 dark:text-slate-500 mb-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                </svg>
                <p class="text-slate-500 dark:text-slate-400">
                    Belum ada percakapan. Mulai percakapan baru dengan AI!
                </p>
            </div>
        </x-card>
        @endforelse
    </div>
</div>
@endsection
