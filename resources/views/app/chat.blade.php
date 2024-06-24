@extends('layouts.app')

@vite(['resources/js/chat.js'])

@section('content')

    <section class="flex flex-col justify-between content-screen">
        <div id="app-splash" class="flex justify-center items-center flex-col w-full p-5"
             style="transform: translateY(-20px);">
            <img class="w-96" alt="logo" src="{{ asset('img/ov-logo.png') }}">
            @auth()
                <p class="logo-text">Hoi {{ Auth::user()->name }}!</p>
            @endauth

            <p class="logo-text" id="welcome-text"></p>
        </div>


        <div id="message-area">
        </div>

        <div>
            <div>
                @guest()
                    <div id="help-text" class="bg-ov-orange rounded-2xl w-32 speech-bubble"
                         style="transform: translateX(12vw) translateY(-20px)">
                        <p style="color: white">Vraag hier waar u
                            naartoe wilt!</p>
                    </div>
                @endguest
            </div>

            <div id="help-box" class="flex flex-col w-full justify-center items-center">
                <div id="help-box-arrow">
                    <svg id="help-box-arrow-icon" xmlns="http://www.w3.org/2000/svg" height="24px"
                         viewBox="0 -960 960 960" width="24px" fill="#000000">
                        <path d="M480-360 280-560h400L480-360Z"/>
                    </svg>
                </div>
                <div class="help-box flex flex-row justify-between items-center p-3">
                    <div class="flex flex-row gap-4">
                        <form class="btn-outline-stylish" action="{{ route('chat.emissions') }}" method="POST">
                            @csrf
                            <input id="emissionsRoute" name="emissionsRoute" type="hidden">
                            <input id="routeObject" name="routeObject" type="hidden">
                            <button type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                     width="24px" fill="#000000">
                                    <path
                                        d="M440-360q-17 0-28.5-11.5T400-400v-160q0-17 11.5-28.5T440-600h120q17 0 28.5 11.5T600-560v160q0 17-11.5 28.5T560-360H440Zm20-60h80v-120h-80v120Zm-300 60q-17 0-28.5-11.5T120-400v-160q0-17 11.5-28.5T160-600h120q17 0 28.5 11.5T320-560v40h-60v-20h-80v120h80v-20h60v40q0 17-11.5 28.5T280-360H160Zm520 120v-100q0-17 11.5-28.5T720-380h80v-40H680v-60h140q17 0 28.5 11.5T860-440v60q0 17-11.5 28.5T820-340h-80v40h120v60H680Z"/>
                                </svg>
                            </button>
                        </form>

                        <a class="btn-outline-stylish">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                 width="24px" fill="#000000">
                                <path
                                    d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Zm280 240q-17 0-28.5-11.5T440-440q0-17 11.5-28.5T480-480q17 0 28.5 11.5T520-440q0 17-11.5 28.5T480-400Zm-160 0q-17 0-28.5-11.5T280-440q0-17 11.5-28.5T320-480q17 0 28.5 11.5T360-440q0 17-11.5 28.5T320-400Zm320 0q-17 0-28.5-11.5T600-440q0-17 11.5-28.5T640-480q17 0 28.5 11.5T680-440q0 17-11.5 28.5T640-400ZM480-240q-17 0-28.5-11.5T440-280q0-17 11.5-28.5T480-320q17 0 28.5 11.5T520-280q0 17-11.5 28.5T480-240Zm-160 0q-17 0-28.5-11.5T280-280q0-17 11.5-28.5T320-320q17 0 28.5 11.5T360-280q0 17-11.5 28.5T320-240Zm320 0q-17 0-28.5-11.5T600-280q0-17 11.5-28.5T640-320q17 0 28.5 11.5T680-280q0 17-11.5 28.5T640-240Z"/>
                            </svg>
                        </a>
                    </div>
                    <div class="flex flex-row gap-4">
                        <form action="{{ route('favorite.store') }}" id="favoriteForm" method="POST"
                              style="margin-bottom: 0">
                            @csrf
                            <input type="text" id="trip_name" name="trip_name"
                                   class="button button-outline border-black mg-0 gray text-small w-75 mg-bottom-4"
                                   placeholder="Plaats hier uw titel"
                                   value="GoGoGo">
                            <input type="text" id="trip_url" name="trip_url"
                                   class="button button-outline border-black mg-0 gray text-small w-75 mg-bottom-4"
                                   placeholder="Plaats hier uw URL" value="nieuwOGom">
                            <button type="submit" class="btn-outline-stylish">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                     width="24px" fill="#000000">
                                    <path
                                        d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <form id="chatbox" class="rounded bg-gray-100 w-full flex justify-center flex-col gap-3 items-center">

                <input class="input-box" type="text" id="chat-box" name="input"
                       placeholder="Typ hier om te praten met MijnOV...">
            </form>
        </div>
    </section>

@endsection
