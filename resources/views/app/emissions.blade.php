@extends('layouts.app')

@vite(['resources/js/emissions.js'])

@section('content')

    <section class="flex flex-col justify-between content-screen items-center">
        <div class="p-5 flex flex-row justify-between w-full md:w-1/2 ">
            <div class="emissions">
                <img alt="user" src="{{ asset('img/icons/car.svg') }}">
                <div class="emissions-circle circle-top emissions-user"></div>
                <div class="emissions-line emissions-user"></div>
                <div class="emissions-circle circle-bottom emissions-user"></div>
            </div>
            <div class="emissions">
                <img alt="ov" src="{{ asset('img/icons/train.svg') }}">
                <div class="emissions-circle circle-top emissions-ov"></div>
                <div class="emissions-line emissions-ov"></div>
                <div class="emissions-circle circle-bottom emissions-ov"></div>
            </div>
        </div>

    </section>

@endsection
