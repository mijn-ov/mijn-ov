@extends('layouts.app')

@section('content')

    <section class="flex flex-col justify-between content-screen">
        <div class="p-5">
{{--            <h1 class="logo-text">Mijn OV</h1>--}}
        </div>

        <div class="flex justify-center items-center flex-col w-full p-5" style="transform: translateY(-40px);">
            <img class="w-96" alt="logo" src="{{ asset('img/ov-logo.png') }}">
            <p class="logo-text" id="welcome-text">‎</p>
        </div>

        <div>
            <div>
                <div class="bg-ov-orange rounded-2xl w-32 speech-bubble" style="transform: translateX(12vw) translateY(-20px)">
                    <p style="color: white">Vraag hier waar u
                        naartoe wilt!</p>
                </div>
            </div>
            <div class="rounded bg-gray-100 w-full flex justify-center">
                <input class="input-box" type="text" id="input" name="input" placeholder="Typ hier om te praten met MijnOV...">
            </div>
        </div>
    </section>

@endsection
