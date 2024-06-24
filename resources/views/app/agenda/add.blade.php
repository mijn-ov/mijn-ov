@extends('layouts.app')

@vite(['resources/js/chat.js'])

@section('content')
    <section class="flex flex-col justify-between content-screen items-center">
        <div class="p-5"
             style="height: 90vh; width: 100vw; overflow-y: scroll; align-items: center; display: flex; flex-direction: column">
            <h1 class="text-center">Voeg een reis toe aan je agenda</h1>
<p>Voeg een van de reizen uit je gesprek toe aan je agenda! In je agenda komen de reizen in een makkelijk overzicht te staan en is de informatie elke week makkelijk opvraagbaar!</p>
            <form class="mt-10" method="POST" action="{{ route('chat.agenda.add') }}" >
                @csrf
                <div class="form-element">
                    <label for="trip">Kies een reis:</label>
                    <select id="trip" name="trip" class="form-control">
                        @foreach($data as $data_entry)
                            @php
                                $submitted_travel = json_decode($data_entry->data, true)
                            @endphp
                            @foreach($submitted_travel['routes'] as $travel)
                                @foreach($travel['legs'] as $route)
                                    <option value="{{ $route['start_address'].' | '.$route['end_address'].' | '.$route['duration']['text'] }}">
                                        {{ $route['start_address'].' naar '.$route['end_address'] }}
                                    </option>
                                @endforeach
                            @endforeach
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-row gap-4 justify-between mt-4">
                    <div class="form-element">
                        <label for="time">Kies een tijd:</label>
                        <input id="time" name="time" type="time" class="form-control">
                    </div>
                    <div class="form-element">
                        <label for="travel_type">Aankomst of vertektijd?</label>
                        <select id="travel_type" name="travel_type">
                            <option value="0">Aankomst</option>
                            <option value="1">Vertrek</option>
                        </select>
                    </div>

                </div>
                    <div class="form-element">
                        <label for="day">Kies een dag uit je agenda:</label>
                        <select id="day" name="day">
                            <option>Maandag</option>
                            <option>Dinsdag</option>
                            <option>Woensdag</option>
                            <option>Donderdag</option>
                            <option>Vrijdag</option>
                            <option>Zaterdag</option>
                            <option>Zondag</option>
                        </select>
                    </div>
                <button type="submit" class="btn btn-primary mt-3">Opslaan</button>
            </form>
        </div>
        <div class="help-box flex flex-row justify-between items-center p-3" style="transform: translateY(-48px)">
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
