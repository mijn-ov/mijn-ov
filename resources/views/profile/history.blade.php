@extends('layouts.app')

@section('content')
    <section class="flex flex-col justify-center content-screen items-center">
        <h1 class="font-bold text-center text-2xl mb-4">Geschiedenis van {{ Auth::user()->name }}</h1>
        <div class="overflow-y-scroll flex flex-col items-center w-full gap-5">
            @foreach($histories as $history)

                <a class="settings w-4/5 md:w-1/2 settings-item" style="align-items: start; margin: 0"
                   href="{{ route('chat.history', [$history->id]) }}">
                    <h2 class="font-bold text-xl">{{ $history->title }}</h2>
                    <p>{{ $history->created_at }}</p>
                </a>
            @endforeach
        </div>
    </section>

@endsection
