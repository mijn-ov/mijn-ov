@extends('layouts.app')

@section('content')
    <section class="flex flex-col justify-center content-screen items-center">
        <h1 class="font-bold text-center text-2xl">Verander wachtwoord</h1>


        <form method="post" action="{{ route('password.update') }}" class="md:w-1/2 p-10 w-full settings-form">
            @csrf
            @method('PUT')
            <h2 class="mb-3 font-bold">Update je wachtwoord</h2>

            <div>
                <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Huidig wachtwoord</label>
                <input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('current_password') border-red-500 @enderror" autocomplete="current-password">
                <!-- Display validation error for current password -->
                @error('current_password')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="update_password_password" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Nieuw wachtwoord</label>
                <input id="update_password_password" name="password" type="password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('password') border-red-500 @enderror" autocomplete="new-password">
                <!-- Display validation error for new password -->
                @error('password')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Bevestig wachtwoord</label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('password_confirmation') border-red-500 @enderror" autocomplete="new-password">
                <!-- Display validation error for password confirmation -->
                @error('password_confirmation')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-4 mt-2">
                <button type="submit" class="btn">
                    Opslaan
                </button>
                <a class="btn-outline" href="{{ route('profile') }}">
                    Terug
                </a>
            </div>
        </form>
    </section>

@endsection
