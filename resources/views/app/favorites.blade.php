@extends('layouts.app')

@section('content')

    <div class="w-100 h-100 flex flex-col items-center justify-content-center">
    <h1>Favoriete Reizen</h1>
    @foreach($favorites as $favorite)
        <a>
            <div class="favoriteCard flex justify-between flex-col">
                <p class="favoriteText">{{$favorite->trip_name}}</p>
                <p style="font-weight: lighter; font-style: italic; font-size: 0.9rem">Klik hier om uw reisadvies te zien</p>
            </div>
        </a>
    @endforeach
    </div>
@endsection
