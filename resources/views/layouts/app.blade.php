@include('components.footer')

    <!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Mijn OV</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+3">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/bootstrap.js'])
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

</head>
<body>
<div id="app" class="bg-gray-100 min-h-screen">
    <main class="py-4 h-full">
        @yield('content')
    </main>

    <nav class="absolute bg-white bottom-0 h-16 rounded w-full">
        <div class="flex flex-row justify-evenly items-center h-full">
            <a href="#" class="scale-125">
                <svg class="fill-ov-purple hover:fill-ov-orange transition ease-in" width="18" height="18"
                     viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M16.6 18L10.3 11.7C9.8 12.1 9.225 12.4167 8.575 12.65C7.925 12.8833 7.23333 13 6.5 13C4.68333 13 3.14583 12.3708 1.8875 11.1125C0.629167 9.85417 0 8.31667 0 6.5C0 4.68333 0.629167 3.14583 1.8875 1.8875C3.14583 0.629167 4.68333 0 6.5 0C8.31667 0 9.85417 0.629167 11.1125 1.8875C12.3708 3.14583 13 4.68333 13 6.5C13 7.23333 12.8833 7.925 12.65 8.575C12.4167 9.225 12.1 9.8 11.7 10.3L18 16.6L16.6 18ZM6.5 11C7.75 11 8.8125 10.5625 9.6875 9.6875C10.5625 8.8125 11 7.75 11 6.5C11 5.25 10.5625 4.1875 9.6875 3.3125C8.8125 2.4375 7.75 2 6.5 2C5.25 2 4.1875 2.4375 3.3125 3.3125C2.4375 4.1875 2 5.25 2 6.5C2 7.75 2.4375 8.8125 3.3125 9.6875C4.1875 10.5625 5.25 11 6.5 11Z"/>
                </svg>
            </a>

            <a href="{{ route('chat') }}" class="scale-150">
                <svg class="fill-ov-purple hover:fill-ov-orange transition ease-in" width="27" height="27"
                     viewBox="0 0 27 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M23.0434 3.95656C20.5462 1.35135 17.0906 -0.0524925 13.5 0.00150147C9.9094 -0.0524925 6.46728 1.35135 3.97006 3.95656C1.35135 6.45378 -0.0524925 9.9094 0.00150147 13.5C-0.0524925 17.0906 1.35135 20.5327 3.95656 23.0299C6.45378 25.6486 9.9094 27.0525 13.5 26.9985C17.0906 27.0525 20.5327 25.6486 23.0299 23.0434C25.6486 20.5462 27.0525 17.0906 26.9985 13.5C27.0525 9.9094 25.6486 6.45378 23.0434 3.95656ZM20.2493 13.5V21.5991H15.5248V14.8499H11.4752V21.5991H6.75075V13.5H4.05105L13.5 4.05105L23.6239 13.5H20.2493Z"/>
                </svg>
            </a>

            @guest()
                <a href="{{ route('login') }}" class="scale-125">
                    <svg class="fill-ov-purple hover:fill-ov-orange transition ease-in"
                         xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                         fill="#e8eaed">
                        <path
                            d="M480-120v-80h280v-560H480v-80h280q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H480Zm-80-160-55-58 102-102H120v-80h327L345-622l55-58 200 200-200 200Z"/>
                    </svg>
                </a>
            @else
                <a href="{{ route('profile') }}" class="scale-125">
                    <svg class="fill-ov-purple hover:fill-ov-orange transition ease-in"
                         xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                         fill="#e8eaed">
                        <path
                            d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/>
                    </svg>
                </a>
            @endguest
        </div>
    </nav>
</div>
</body>
</html>
