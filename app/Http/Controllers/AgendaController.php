<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    public function redirect()
    {
        return redirect(route('agenda.view', 'maandag'));
    }

    public function view($day = null)
    {
        // Default to "maandag" if $day is not set
        if (!isset($day)) {
            $day = "maandag";
        }

        // List of valid days
        $validDays = ['maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag', 'zondag'];

        // Check if $day is not in the list of valid days
        if (!in_array($day, $validDays)) {
            $day = "maandag";
        }

        $agendaEntries = Agenda::where('day', $day)
            ->where('user_id', Auth::id())
            ->get();


        return view('app.agenda.view', ['day' => $day, 'agendaEntries' => $agendaEntries]);
    }

}
