@extends('layouts.app')

@vite(['resources/js/chat.js'])

@section('content')

    <section class="flex flex-col justify-between content-screen">
        <div id="app-splash" class="flex justify-center items-center flex-col w-full p-5"
             style="transform: translateY(-20px);">
            @if($messages === null)
                <img class="w-52" alt="logo" src="{{ asset('img/ov-logo.png') }}">
                @auth()
                    <div class="logo-text">
                        <p>Hoi {{ Auth::user()->name }}!</p>
                        <p>Hoe kan MijnOV je helpen?</p>
                    </div>
                @else()
                    <div class="logo-text">
                        <p id="greeting"></p>
                        <script>
                            function updateGreeting() {
                                let now = new Date();
                                let hours = now.getHours();
                                let greetingText;

                                if (hours >= 5 && hours < 12) {
                                    greetingText = "Goedemorgen!";
                                } else if (hours >= 12 && hours < 17) {
                                    greetingText = "Goedemiddag!";
                                } else if (hours >= 17 && hours < 21) {
                                    greetingText = "Goedenavond!";
                                } else {
                                    greetingText = "Goedenacht!";
                                }

                                document.getElementById("greeting").innerText = greetingText;
                            }

                            updateGreeting();
                        </script>
                        <p>Hoe kan MijnOV je helpen?</p>
                    </div>
                @endauth
            @endif

            @auth()
                @if($histories !== null)
                    <div id="history"
                         class="history">
                        @foreach($histories as $history)
                            <a href="{{ route('chat.history', [$history->id]) }}">
                                <div class="history-button" data-id="{{ $history->id }}">
                                    <svg class="size-15" xmlns="http://www.w3.org/2000/svg" height="24px"
                                         viewBox="0 -960 960 960" width="24px" fill="#9f9f9f">
                                        <path
                                            d="M480-120q-138 0-240.5-91.5T122-440h82q14 104 92.5 172T480-200q117 0 198.5-81.5T760-480q0-117-81.5-198.5T480-760q-69 0-129 32t-101 88h110v80H120v-240h80v94q51-64 124.5-99T480-840q75 0 140.5 28.5t114 77q48.5 48.5 77 114T840-480q0 75-28.5 140.5t-77 114q-48.5 48.5-114 77T480-120Zm112-192L440-464v-216h80v184l128 128-56 56Z"/>
                                    </svg>
                                    <div>
                                        <p>{{ $history->title }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif

                @if($messages !== null)
                    <script>
                        const chatHistoryData = {!! $messages->toJson() !!};
                    </script>
                @endif
            @endauth
        </div>


        <div id="message-area" class="no-scroll">
            {{--                        <form action="{{ route('favorite.store') }}" method="POST">--}}
            {{--                            @csrf--}}
            {{--                            <input type="text" id="trip_name" name="trip_name"--}}
            {{--                                   class="button button-outline border-black mg-0 gray text-small w-75 mg-bottom-4"--}}
            {{--                                   placeholder="Plaats hier uw titel"--}}
            {{--                                   value="nieuw!">--}}
            {{--                            <input type="text" id="trip_url" name="trip_url"--}}
            {{--                                   class="button button-outline border-black mg-0 gray text-small w-75 mg-bottom-4"--}}
            {{--                                   placeholder="Plaats hier uw URL" value="nieuw">--}}
            {{--                            <button type="submit">Submit</button>--}}
            {{--                        </form>--}}
        </div>

        <div>
            <div>
                @guest()
                    <div id="help-text" class="bg-ov-orange rounded-2xl w-32 speech-bubble"
                         style="transform: translateX(12vw) translateY(-70px)">
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
                                        d="M440-360q-17 0-28.5-11.5T400-400v-160q0-17 11.5-28.5T440-600h120q17 0 28.5 11.5T600-560v160q0 17-11.5 28.5T560-360H440Zm20-60h80v-120h-80v120Zm-300 60q-17 0-28.5-11.5T120-400v-160q0-17 11.5-28.5T160-600h120q17 0 28.5 11.5T320-560v40h-60v-20h-80v120h80v-20h60v40q0 17-11.5 28.5T280-360H160Zm520 120v-100q0-17 11.5-28.5T720-380h80v-40H680v-60h140q17 0 28.5 11.5T860-440v60q0 17-11.5 28.5T820-340h-80v40h120v60H680Z"/>
                                </svg>
                            </a>
                            <a id="agenda-button" class="btn-outline-stylish">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                     width="24px" fill="#000000">
                                    <path
                                        d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Zm280 240q-17 0-28.5-11.5T440-440q0-17 11.5-28.5T480-480q17 0 28.5 11.5T520-440q0 17-11.5 28.5T480-400Zm-160 0q-17 0-28.5-11.5T280-440q0-17 11.5-28.5T320-480q17 0 28.5 11.5T360-440q0 17-11.5 28.5T320-400Zm320 0q-17 0-28.5-11.5T600-440q0-17 11.5-28.5T640-480q17 0 28.5 11.5T680-440q0 17-11.5 28.5T640-400ZM480-240q-17 0-28.5-11.5T440-280q0-17 11.5-28.5T480-320q17 0 28.5 11.5T520-280q0 17-11.5 28.5T480-240Zm-160 0q-17 0-28.5-11.5T280-280q0-17 11.5-28.5T320-320q17 0 28.5 11.5T360-280q0 17-11.5 28.5T320-240Zm320 0q-17 0-28.5-11.5T600-280q0-17 11.5-28.5T640-320q17 0 28.5 11.5T680-280q0 17-11.5 28.5T640-240Z"/>
                                </svg>
                            </a>
                            <a id="map-button" class="btn-outline-stylish">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 576 512"
                                     width="24px" fill="#000000">
                                    <path
                                        d="M565.6 36.2C572.1 40.7 576 48.1 576 56V392c0 10-6.2 18.9-15.5 22.4l-168 64c-5.2 2-10.9 2.1-16.1 .3L192.5 417.5l-160 61c-7.4 2.8-15.7 1.8-22.2-2.7S0 463.9 0 456V120c0-10 6.1-18.9 15.5-22.4l168-64c5.2-2 10.9-2.1 16.1-.3L383.5 94.5l160-61c7.4-2.8 15.7-1.8 22.2 2.7zM48 136.5V421.2l120-45.7V90.8L48 136.5zM360 422.7V137.3l-144-48V374.7l144 48zm48-1.5l120-45.7V90.8L408 136.5V421.2z"/>
                                </svg>
                            </a>
                        </div>
                        {{--                        <div class="flex flex-row gap-4">--}}
                        {{--                            <a class="btn-outline-stylish">--}}
                        {{--                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"--}}
                        {{--                                     width="24px" fill="#000000">--}}
                        {{--                                    <path--}}
                        {{--                                        d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/>--}}
                        {{--                                </svg>--}}
                        {{--                            </a>--}}
                        {{--                        </div>--}}
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

                <form id="chatbox" style="transform: translateY(-50px)"
                      class="rounded bg-gray-100 w-full flex justify-center flex-col gap-3 items-center">
                    <input class="input-box" type="text" id="chat-box" name="input" autocomplete="off"
                           placeholder="Typ hier om te praten met MijnOV...">
                </form>
        </div>
    </section>

@endsection
