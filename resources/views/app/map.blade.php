@extends('layouts.app')

@vite(['resources/js/map.js'])

@section('content')

    <section class="relative w-full h-screen">
        <div id="map" class="absolute top-0 left-0 w-full h-full">
            <!-- Map container -->
        </div>
        <div class="help-box absolute bottom-24 left-12 w-full flex flex-row justify-between items-center p-3 z-10" style="background-color: rgba(255, 255, 255, 1);">
            <div class="flex flex-row gap-4">
                <a class="btn-outline-stylish" href="{{ route('chat.history', [$id]) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                        <path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

@endsection
