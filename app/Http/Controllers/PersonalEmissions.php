<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonalEmissions extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    public function personalEmissions()
    {

        $user = User::find(Auth::id());

        $km_driven = $user->car_emissions;
        $ovEmissions = $user->ov_emissions;


        return view('app.personal-emissions', compact('ovEmissions', 'km_driven'));
    }

    function updateEmissions(Request $request)
    {
        $request->validate([
            'ovEmissionsValue' => 'required',
            'carKm' => 'required',
        ]);

        $user = User::find(Auth::id());

        $newEmissions = $request->input('ovEmissionsValue');
        $carKm = $request->input('carKm');

        $km_driven = $user->car_emissions;
        $ovEmissions = $user->ov_emissions;

        $km_driven += $carKm;
        $ovEmissions += $newEmissions;
        $user->ov_emissions = $ovEmissions;
        $user->car_emissions = $km_driven;
        $user->save();
        return view('app.personal-emissions', compact('ovEmissions', 'km_driven'));


    }
}
