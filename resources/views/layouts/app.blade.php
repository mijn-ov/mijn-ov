@include('components.footer')

    <!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>MijnOV</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/public/apple-touch-icon.png">
    <link rel="manifest" href="/public/site.webmanifest">
    <link rel="mask-icon" href="/public/safari-pinned-tab.svg" color="#cb4793">
    <meta name="msapplication-TileColor" content="#cb4793">
    <meta name="theme-color" content="#ffffff">

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

    <nav class="sticky bg-white bottom-0 h-16 rounded w-full" style="z-index: 10000">
        <div class="flex flex-row justify-evenly items-center h-full">
            <a href="{{ route('favorites') }}" class="scale-125">
                <svg class="fill-ov-purple hover:fill-ov-orange transition ease-in" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/></svg>
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
