@extends('layouts.app')

@vite(['resources/js/map.js'])

@section('content')

    <section class="flex flex-col justify-between content-screen items-center">
        <div class="p-5 flex flex-row justify-between w-full md:w-1/2" style="height: 75vh" id="map">
            <!-- Map container -->
        </div>
    </section>

@endsection
