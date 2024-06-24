<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmissionsController extends Controller
{

    function viewEmissions(Request $request)
    {

        $routeDetails = $request->input('routeObject');
        $publicRoute = $request->input('emissionsRoute');

        return view('app.emissions', compact('routeDetails', 'publicRoute'));
    }

}
