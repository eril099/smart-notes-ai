@extends('layouts.auth')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-slate-100">
    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-8">Smart Notes AI</h1>
        <form method="POST" action="/login">
            @csrf

            <div class="mb-4">
                <label class="block mb-2">Username</label>
                <input type="text" name="username" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block mb-2">Password</label>
                <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <x-button>
                Login
            </x-button>
        </form>
    </div>
</div>
@endsection