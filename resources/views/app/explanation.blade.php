@extends('layouts.app')

@vite(['resources/js/chat.js'])

@section('content')
    <section class="flex flex-col justify-between content-screen items-center">
        <div class="p-5" style="height: 90vh; overflow-y: scroll">
            <h1>Waar we ons advies op gebaseerd hebben:</h1>

            <div class="flex md:flex-row flex-col gap-5">
                <div>
                    <div>
                        <h3>Beginpunt</h3>
                        @php
                            $route = json_decode($data[0]['data'], true);
                        @endphp

                        <p class="text">{{ $route['routes'][0]['legs'][0]['start_address'] }}</p>
                    </div>
                    <div>
                        <h3>Eindpunt</h3>
                        <p class="text">{{ $route['routes'][0]['legs'][0]['end_address'] }}</p>
                    </div>
                    <div>
                        <h3>Vertrektijd</h3>
                        <p class="text">{{ $route['routes'][0]['legs'][0]['departure_time']['text'] }}</p>
                    </div>
                </div>
                <div class="md:w-1/2 w-full flex items-center flex-col">
                    @foreach($route['routes'][0]['legs'][0]['steps'] as $step)
                        <div class="route-partial">
                            <div class="leg-header"><p>{{ $step['html_instructions'] }}
                                    ● {{ $step['duration']['text'] }}</p>

                                @if(isset($step['transit_details']))
                                    <p>{{ $step['transit_details']['line']['vehicle']['name'] }}</p>
                                @else
                                    <p>Lopen</p>
                                @endif
                            </div>
                            @if(isset($step['transit_details']))
                                <div><p class="station">Opstap:</p>
                                    <p>{{ $step['transit_details']['departure_stop']['name'] }}</p></div>
                                <img src="{{ asset('img/arrow-right.svg') }}" class="arrowImg">
                                <div><p class="station">Afstap:</p>
                                    <p>{{ $step['transit_details']['arrival_stop']['name'] }}</p></div>
                            @else
                                <p>{{ $step['html_instructions'] }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <p class="text mt-10 font-bold">Mijn OV kan je berichten verkeerd begrijpen! Controleer altijd of de juiste stations
                en
                tijden hierboven
                zijn
                aangegeven!</p>

            <div class="mt-10">
                <p class="text-ov-orange font-extrabold">Informatie via:</p>
                <ol>
                    <li class="text-ov-orange">9292</li>
                    <li class="text-ov-orange">Google Maps</li>
                    <li class="text-ov-orange">NS</li>
                </ol>
            </div>
        </div>
        <div class="help-box flex flex-row justify-between items-center p-3" style="transform: translateY(-8px)">
            <div class="flex flex-row gap-4">
                <a class="btn-outline-stylish" href="{{ route('chat.history', [$id]) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                         fill="#000000">
                        <path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>
@endsection
