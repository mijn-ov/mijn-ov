@extends('layouts.app')

@vite(['resources/js/chat.js'])

@section('content')
    <section class="flex flex-col justify-between content-screen items-center">
        <div class="p-5"
             style="height: 90vh; width: 100vw; overflow-y: scroll; align-items: center; display: flex; flex-direction: column">
            <h1 class="text-center">Voeg een reis toe aan je agenda</h1>
            <form class="mt-10" method="POST" action="{{ route('agenda.create.save') }}" >
                @csrf
                <div class="flex flex-col gap-4 w-full justify-between mt-4">
                    <div class="form-element">
                        <label for="start_address">Geef een beginpunt:</label>
                        <input id="start_address" name="start_address" type="text" class="form-control w-full">
                        @error('start_address')
                        <span class="text-red-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-element">
                        <label for="end_address">Geef een eindpunt:</label>
                        <input id="end_address" name="end_address" type="text" class="form-control w-full">
                        @error('end_address')
                        <span class="text-red-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>
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
                <a class="btn-outline-stylish" href="{{ route('agenda') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                         fill="#000000">
                        <path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>
@endsection
