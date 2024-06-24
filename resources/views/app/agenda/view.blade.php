@extends('layouts.app')

@vite(['resources/js/agenda.js'])

@section('content')
    <section class="flex flex-col justify-between content-screen items-center">
        <div class="p-5"
             style="height: 90vh; width: 100vw; overflow-y: scroll; align-items: center; display: flex; flex-direction: column">
            <h1 class="text-center">Agenda</h1>

            <a id="add" href="{{ route('agenda.create') }}">
                +
            </a>

            <div class="flex flex-row justify-between w-full items-center">
                <a id="back" class="btn "><</a>
                <h3>{{$day}}</h3>
                <a id="next" class="btn">></a>
            </div>
            <div id="agenda" class="agenda">
                @foreach($agendaEntries as $entry)
                    @php
                        $timeParts = explode(':', $entry->time);
                        $hours = (int) $timeParts[0];
                        $minutes = (int) $timeParts[1];
                        $totalMinutes = ($hours * 60) + $minutes;

                        if ($entry->travel_type === 1) {
                            $position = $totalMinutes;
                        } else {
                            $position = $totalMinutes - $entry->duration;
                        }
                    @endphp

                    <div class="agenda-entry" data-trip="{{ $entry }}" style="height: {{ $entry->duration }}px; margin-bottom: -{{ $entry->duration }}px; transform: translateY({{ $position }}px)">
                    <p>{{ $entry->start_address }} naar {{ $entry->end_address }}</p>
            </div>
            @endforeach

            @foreach(range(0, 23) as $i)
                <div class="hour">{{$i}}:00</div>
            @endforeach

            </div>

        </div>
    </section>
@endsection
