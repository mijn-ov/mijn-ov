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
            <form action="{{ route('favorite.store') }}" method="POST">
                @csrf
                <input type="text" id="trip_name" name="trip_name"
                       class="button button-outline border-black mg-0 gray text-small w-75 mg-bottom-4"
                       placeholder="Plaats hier uw titel"
                       value="nieuw!">
                <input type="text" id="trip_url" name="trip_url"
                       class="button button-outline border-black mg-0 gray text-small w-75 mg-bottom-4"
                       placeholder="Plaats hier uw URL" value="nieuw">
                <button type="submit">Submit</button>
            </form>
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
