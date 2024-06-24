<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\History;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Http\Request;
use OpenAI\Factory;

class ChatController extends Controller
{
    public function viewChat()
    {
        if (Auth::user()) {
            $histories = History::where('user_id', Auth::id())
                ->latest()
                ->take(3)
                ->get();

            $messages = null;

            return view('app.chat', ['histories' => $histories, 'messages' => $messages]);
        } else {
            $messages = null;
            return view('app.chat', ['messages' => $messages]);
        }
    }

    public function loadHistory($id)
    {
        $history = History::where('id', $id)->first();

        if ($history->user_id) {
            // Check if the history exists and if the user ID matches the authenticated user
            if (!$history || $history->user_id !== (Auth::id())) {
                return redirect(route('chat'));
            }
            $messages = Message::where('history_id', $id)
                ->where('user_id', Auth::id() || null)
                ->get();
        } else {
            $messages = Message::where('history_id', $id)
                ->get();
        }

        $histories = null;


        return view('app.chat', ['messages' => $messages, 'histories' => $histories]);
    }


    public function viewEmissions($id)
    {
        return view('app.emissions', ['id' => $id]);
    }

    public function viewMap($id)
    {
        return view('app.map', ['id' => $id]);
    }

    public function viewExplanation($id)
    {
        $data = Message::where('history_id', $id)
            ->whereNotNull('data')
            ->get();

        return view('app.explanation', ['id' => $id, 'data' => $data]);
    }

    public function viewAgenda($id)
    {
        $data = Message::where('history_id', $id)
            ->whereNotNull('data')
            ->get();


        return view('app.agenda.add', ['id' => $id, 'data' => $data]);
    }

    public function addToAgenda(Request $request)
    {
        $request->validate([
            'day' => 'required|string',
            'time' => 'required|string',
            'trip' => 'required|string',
            'travel_type' => 'required|boolean',
        ]);

        $trip = $request->input('trip');

        $addresses = explode(' | ', $trip);

        if(count($addresses) == 3) {
            $start_address = $addresses[0];
            $end_address = $addresses[1];
            $duration = $addresses[2];

            $durationParts = explode(' ', $duration); // Split by space
            $hours = (int) $durationParts[0]; // Convert first element to integer (hours)
            $minutes = explode('min', $durationParts[1])[0]; // Extract minutes before "min"
            $minutes = (int) $minutes; // Convert minutes to integer

            $totalMinutes = ($hours * 60) + $minutes;

            $agenda = new Agenda();
            $agenda->user_id = auth()->id(); // Assuming you want to associate the current authenticated user
            $agenda->day = $request->input('day');
            $agenda->time = $request->input('time');
            $agenda->start_address = $start_address;
            $agenda->end_address = $end_address;
            $agenda->duration = $totalMinutes;
            $agenda->travel_type = $request->input('travel_type');;
            $agenda->save();

            return redirect(route('agenda.view', strtolower($agenda->day)));
        } else {
            return response()->json(['error' => 'Invalid trip format'], 400);
        }
    }

    public function submitMessage()
    {
        // Get the JSON data from the request body
        $json = file_get_contents('php://input');

        // Decode the JSON data
        $data = json_decode($json);

        // Example of configuring API client (adjust according to your actual implementation)
        $client = \OpenAI::factory()
            ->withBaseUri(env('OPENAI_API_BASE') . 'openai/deployments/' . env('ENGINE_NAME'))
            ->withHttpHeader('api-key', env('AZURE_OPENAI_API_KEY'))
            ->withQueryParam('api-version', env('OPENAI_API_VERSION'))
            ->make();

        // Constructing the prompt based on received data
        $prompt = '
        You are now a travel assistant AI. Your task is to create a JSON file for the user containing the necessary travel data.

        1. Extract the starting point ("origin") and destination ("destination") from the user\'s message. Include the city name (e.g., "Blaak, Rotterdam") for metro stations like "Blaak" or "Beurs".
        2. Use "current location" if the user requests it for either the starting point or destination.
        3. Provide a message for the user under "message". Start with "Hier is uw route van _ naar _" and add a personal touch.
        4. Identify and add other relevant parameters from the user\'s message to the "parameters" field. Example parameters include "?lang", "&fromStation", "&toStation", etc.
        5. If the origin or destination cannot be determined, include a polite error message in the "message" field requesting more information.
        6. Create a suitable title for the conversation, including the identified starting or ending point if available.
        7. Utilize previous user questions if information is missing. For example, if a previous trip had an endpoint, use that as the starting point if no starting point is provided.

        Summary:
        - Extract and format the origin and destination from the user\'s message, including city names for metro stations.
        - Use "current location" when requested.
        - Refer to the user\'s message history when necessary.
        - Craft a personalized message starting with "Hier is uw route van _ naar _".
        - Include other identified parameters in the "parameters" field.
        - Provide a title reflecting the conversation\'s locations.
        - In case of missing information, respond with a JSON containing a polite error message in the "message" field.

        Always respond in JSON format, even if an error occurs.

        Here is the exact JSON format. Ensure these five fields are always present and adhere to their names:
        {
            "origin": "origin",
            "destination": "destination",
            "message": "Your message to the user",
            "parameters": "Any additional parameters found",
            "title": "The title of the conversation with the locations."
        }

        Here is the user\'s current location (if applicable):
        ' . $data->location . '

        Here is the user\'s message:
        ' . $data->message . '

        Here are all the previous user messages:
        ' . $data->previousQuestions;

        // Make the API request to the OpenAI service (example implementation)
        $result = $client->chat()->create([
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0,
        ]);

        // Prepare the response
        $response = array(
            'response' => $result->choices[0]->message->content,
        );

        // Encode the response as JSON
        $json_response = json_encode($response);

        // Set the Content-Type header to application/json
        header('Content-Type: application/json');

        // Send the response back to the client
        echo $json_response;
    }


    public function create(Request $request)
    {
        $request->validate([
            'history' => 'required|string',
        ]);

        $history = History::create([
            'title' => $request->input('history'),
            'user_id' => Auth::id(),
        ]);

        return response()->json(['chatID' => $history->id]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string'
        ]);

        $history = History::find($id);

        $history->title = $request->input('title');

        $history->save();

        return response()->json(['title' => $history->title, 'id' => $history->id]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'messages' => 'required',
            'history_id' => 'required|integer',
            'data' => 'nullable',
        ]);

        $messageContent = json_encode($request->input('messages'));

        $message = Message::create([
            'message' => $messageContent,
            'data' => $request->input('data'),
            'history_id' => $request->input('history_id'),
            'user_id' => Auth::id(),
        ]);

        return response()->json(['status' => 'success']);
    }
}
