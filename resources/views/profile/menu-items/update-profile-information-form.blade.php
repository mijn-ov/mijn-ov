@extends('layouts.app')

@section('content')

    <section class="flex flex-col justify-center content-screen items-center">
        <h1 class="font-bold text-center text-2xl">Persoonlijke informatie</h1>

    <form method="post" action="{{ route('profile.update') }}" class="md:w-1/2 p-10 w-full settings-form">
        @csrf
        @method('patch')
        <h2 class="mb-3 font-bold">Update je persoonlijke informatie</h2>
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('Naam') }}</label>
            <input id="name" name="name" type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name') border-red-500 @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('email') border-red-500 @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror

        </div>

        <div class="flex items-center gap-4 mt-2">
            <button type="submit" class="btn">
                {{ __('Opslaan') }}
            </button>
            <a class="btn-outline" href="{{ route('profile') }}">
                Terug
            </a>
        </div>
    </form>
</section>

@endsection
