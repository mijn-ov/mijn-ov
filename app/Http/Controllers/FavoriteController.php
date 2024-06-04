<?php

namespace App\Http\Controllers;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    function viewFavorites(){
        $favorites = Favorite::where('user_id', '=', Auth::user()->id)->get();

        return view('app.favorites', compact('favorites'));
    }

    function store(Request $request){

        $request->validate([
            'trip_name' => 'required',
            'trip_url' => 'required',
        ]);

        $favorite = new Favorite();
        $favorite->trip_name = $request->input('trip_name');
        $favorite->trip_url = $request->input('trip_url');
        $favorite->user_id = Auth::user()->id;
        $favorite->save();
        return redirect(route('favorites'));
    }
}
