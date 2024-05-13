@extends('layouts.app')

@vite(['resources/js/chat.js'])

@section('content')

    <section class="flex flex-col justify-between content-screen">
        <div id="app-splash" class="flex justify-center items-center flex-col w-full p-5"
             style="transform: translateY(40px);">
            <img class="w-96" alt="logo" src="{{ asset('img/ov-logo.png') }}">
            <p class="logo-text" id="welcome-text">‎</p>
        </div>



        <div id="message-area">
        </div>

        <div>
            <div>
                <div id="help-text" class="bg-ov-orange rounded-2xl w-32 speech-bubble"
                     style="transform: translateX(12vw) translateY(-20px)">
                    <p style="color: white">Vraag hier waar u
                        naartoe wilt!</p>
                </div>
            </div>
            <form id="chatbox" class="rounded bg-gray-100 w-full flex justify-center">
                <input class="input-box" type="text" id="chat-box" name="input"
                       placeholder="Typ hier om te praten met MijnOV...">
            </form>
        </div>
    </section>

@endsection
