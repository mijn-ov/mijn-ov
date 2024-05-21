@extends('layouts.app')

@section('content')
    <section class="flex flex-col justify-center content-screen items-center">
        <h1 class="font-bold text-center text-2xl">Verwijder account</h1>
                    <form method="post" action="{{ route('profile.destroy') }}" class="md:w-1/2 p-10 w-full settings-form">
                        @csrf
                        @method('delete')

                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Weet je zeker dat je je account wilt verwijderen?
                        </h2>

                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Wanneer je je account verwijderd, wordt ook alle data zoals chatgeschiedenis en geplande reizen verwijderd. Voer je wachtwoord in als je je account permanent wilt verwijderen.
                        </p>

                        <div class="mt-6">
                            <label for="password" class="sr-only">Wachtwoord</label>
                            <input id="password" name="password" type="password" class="mt-1 block w-3/4 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Wachtwoord">
                            <!-- Display validation error for password -->
                            @if ($errors->userDeletion->has('password'))
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $errors->userDeletion->first('password') }}</p>
                            @endif
                        </div>

                        <div class="flex items-center gap-4 mt-5">
                            <button type="submit" class="btn-outline">
                                Verwijder je account
                            </button>
                            <a class="btn" href="{{ route('profile') }}">
                                Annuleren
                            </a>

                        </div>
                    </form>

    </section>

@endsection
