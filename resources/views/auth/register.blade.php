@extends('layouts.app')

@section('content')
    <section class="flex flex-col justify-center content-screen items-center">
            <form method="POST" action="{{ route('register') }}" class="md:w-1/2 p-10 w-full">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm  mb-2">Voor- en achternaam</label>
                    <input id="name" type="text"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
                           name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    @error('name')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email"
                           class="block text-gray-700 text-sm  mb-2">{{ __('Email adres') }}</label>
                    <input id="email" type="email"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror"
                           name="email" value="{{ old('email') }}" required autocomplete="email">
                    @error('email')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password"
                           class="block text-gray-700 text-sm  mb-2">{{ __('Wachtwoord') }}</label>
                    <input id="password" type="password"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror"
                           name="password" required autocomplete="new-password">
                    @error('password')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password-confirm"
                           class="block text-gray-700 text-sm  mb-2">{{ __('Herhaal wachtwoord') }}</label>
                    <input id="password-confirm" type="password"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           name="password_confirmation" required autocomplete="new-password">
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit"
                            class="btn">
                        {{ __('Registreren') }}
                    </button>
                </div>
            </form>
        </section>
@endsection
