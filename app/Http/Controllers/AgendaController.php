<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use DateTime;
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

    public function edit($id)
    {
        $data = Agenda::where('id', $id)
            ->first();

        return view('app.agenda.edit', ['id' => $id, 'data' => $data]);
    }

    public function save(Request $request, $id)
    {
        $request->validate([
            'day' => 'required|string',
            'time' => 'required|string',
            'travel_type' => 'required|boolean',
            'start_address' => 'required|string',
            'end_address' => 'required|string'
        ]);

        // Find the existing agenda record by $id
        $agenda = Agenda::findOrFail($id);

        // Update the agenda record with new data
        $agenda->day = $request->input('day');
        $agenda->time = $request->input('time');
        $agenda->start_address = $request->input('start_address');;
        $agenda->end_address = $request->input('end_address');;
        $agenda->duration = '60';
        $agenda->travel_type = $request->input('travel_type');
        $agenda->save();

        return redirect(route('agenda.view', strtolower($request->input('day'))));
    }

    public function editTime(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'duration' => 'required|string',
            'departure_time' => 'required|string',
            'arrival_time' => 'required|string',
        ]);

        // Find the agenda record by ID
        $agenda = Agenda::find($request->input('id'));

        // Update agenda properties
        if ($agenda) {
            if ($agenda->travel_type === 0) {
                $agenda->time = $request->input('arrival_time');
            } else {
                $agenda->time = $request->input('departure_time');
            }
            $durationMinutes = $this->convertDurationToMinutes($request->input('departure_time'), $request->input('arrival_time'));

            $agenda->duration = $durationMinutes;

            // Save the updated agenda
            $agenda->save();

            // Return a JSON response indicating success
            return response()->json(['success' => true, 'agenda' => $agenda]);
        } else {
            // If agenda with given ID is not found, return a JSON response with an error message
            return response()->json(['success' => false, 'message' => 'Agenda not found'], 404);
        }
    }

    /**
     * Convert duration string like "2 uur en 10 minuten" to total minutes.
     *
     * @param string $durationText
     * @return int
     */
    private function convertDurationToMinutes($departureTime, $arrivalTime)
    {
        // Convert times to DateTime objects for easier comparison
        $departure = DateTime::createFromFormat('H:i', $departureTime);
        $arrival = DateTime::createFromFormat('H:i', $arrivalTime);

        // Calculate the difference in minutes
        $interval = $arrival->diff($departure);
        $hours = $interval->h;
        $minutes = $interval->i;

        return ($hours * 60) + $minutes;
    }

    public function delete($id)
    {
        try {
            $agenda = Agenda::findOrFail($id);
            $day = $agenda->day;

            $agenda->delete();

            return redirect()->route('agenda.view', strtolower($day))->with('success', 'Agenda item deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('agenda.view', strtolower($day))->with('error', 'Failed to delete agenda item');
        }
    }

    public function create()
    {
        return view('app.agenda.create');
    }

    public function createSave(Request $request)
    {
        $request->validate([
            'day' => 'required|string',
            'time' => 'required|string',
            'travel_type' => 'required|boolean',
            'start_address' => 'required|string',
            'end_address' => 'required|string'
        ]);


        $agenda = new Agenda();
        $agenda->user_id = auth()->id(); // Assuming you want to associate the current authenticated user
        $agenda->day = $request->input('day');
        $agenda->time = $request->input('time');
        $agenda->start_address = $request->input('start_address');;
        $agenda->end_address = $request->input('end_address');;
        $agenda->duration = '60';
        $agenda->travel_type = $request->input('travel_type');
        $agenda->save();

        return redirect(route('agenda.view', strtolower($agenda->day)));
}

}
