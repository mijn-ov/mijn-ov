@extends('layouts.app')

@section('content')
    <section class="flex flex-col justify-center content-screen items-center">
        <img class="w-1/2 md:w-1/4" alt="logo" src="{{ asset('img/ov-logo.png') }}">
        <form method="POST" action="{{ route('login') }}" class="md:w-1/2 p-10 w-full">
            @csrf

            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>

                <div class="mt-1">
                    <input id="email" type="email"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('email') border-red-500 @enderror"
                           name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                    <span class="text-red-500 text-sm mt-1" role="alert">
                <strong>{{ $message }}</strong>
            </span>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Wachtwoord') }}</label>

                <div class="mt-1">
                    <input id="password" type="password"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('password') border-red-500 @enderror"
                           name="password" required autocomplete="current-password">

                    @error('password')
                    <span class="text-red-500 text-sm mt-1" role="alert">
                <strong>{{ $message }}</strong>
            </span>
                    @enderror
                </div>
            </div>

            <div class="mb-6 flex items-center">
                <input class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" type="checkbox"
                       name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="ml-2 block text-sm text-gray-900" for="remember">
                    {{ __('Onthoud me!') }}
                </label>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="btn">
                    Login
                </button>

                @if (Route::has('password.request'))
                    <a class="btn-outline" href="{{ route('register') }}">
                        {{ __('Registreren') }}
                    </a>
                @endif
            </div>
        </form>
    </section>

@endsection
