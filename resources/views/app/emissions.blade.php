@extends('layouts.app')

@vite(['resources/js/emissions.js'])

@section('content')

    <section class="flex flex-col justify-between content-screen items-center">
        <div class="p-5 flex flex-row justify-between w-full md:w-1/2" style="height: 75vh">
            <div class="emissions">
                <img alt="user" src="{{ asset('img/icons/car.svg') }}">
                <div class="emissions-circle circle-top emissions-user"></div>
                <div class="emissions-line emissions-user"></div>
                <div class="emissions-circle circle-bottom emissions-user"></div>
            </div>
            <div class="emissions">
                <img alt="ov" src="{{ asset('img/icons/train.svg') }}">
                <div class="emissions-circle circle-top emissions-ov"></div>
                <div class="emissions-line emissions-ov"></div>
                <div class="emissions-circle circle-bottom emissions-ov"></div>
            </div>
        </div>

        <div class="help-box flex flex-row justify-between items-center p-3" style="transform: translateY(-48px)">
            <div class="flex flex-row gap-4">
                <a class="btn-outline-stylish" href="{{ route('chat.history', [$id]) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"/></svg>
                </a>
            </div>
        </div>
    </section>

@endsection
