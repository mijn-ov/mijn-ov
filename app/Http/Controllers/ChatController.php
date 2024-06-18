<?php

namespace App\Http\Controllers;

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

        // Check if the history exists and if the user ID matches the authenticated user
        if (!$history || $history->user_id !== (Auth::id())) {
            return redirect(route('chat'));
        }


        $messages = Message::where('history_id', $id)
            ->where('user_id', Auth::id() || null)
            ->get();
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

    public function submitMessage()
    {
        // Get the JSON data from the request body
        $json = file_get_contents('php://input');

        // Decode the JSON data
        $data = json_decode($json);

        $client = \OpenAI::factory()
            ->withBaseUri(env('OPENAI_API_BASE') . 'openai/deployments/' . env('ENGINE_NAME'))
            ->withHttpHeader('api-key', env('AZURE_OPENAI_API_KEY'))
            ->withQueryParam('api-version', env('OPENAI_API_VERSION'))
            ->make();


        $prompt = '
You are now a travel assistant AI. Your task is to create a JSON file for the user containing the necessary travel data.

1. Extract the starting point ("origin") and destination ("destination") from the user\'s message. If the starting point or destination is a metro station like "Blaak" or "Beurs", make sure to include the city name (e.g., "Blaak, Rotterdam").
2. If the user requests the current location for the starting point or destination, use "current location" for that point. Additionally, use the user\'s current location from the GPS data (provided in JSON format) and place these respectively in "originLng" and "originLat" for the starting point, or in "destinationLng" and "destinationLat" for the destination.
3. Leave a message for the user under "message". Start the message with "Hier is uw route van _ naar _" and add a personal touch.
4. Look for other possible parameters in the user\'s message and add these to the "parameters" field. Use the following list of possible parameters:
   "[?lang][&fromStation][&originUicCode][&originLat][&originLng][&originName][&toStation][&destinationUicCode][&destinationLat][&destinationLng][&destinationName][&viaStation][&viaUicCode][&viaLat][&viaLng][&originWalk][&originBike][&originCar][&destinationWalk][&destinationBike][&destinationCar][&dateTime][&searchForArrival][&departure][&context][&shorterChange][&addChangeTime][&minimalChangeTime][&viaWaitTime][&originAccessible][&travelAssistance][&nsr][&travelAssistanceTransferTime][&accessibilityEquipment1][&accessibilityEquipment2][&searchForAccessibleTrip][&filterTransportMode][&localTrainsOnly][&excludeHighSpeedTrains][&excludeTrainsWithReservationRequired][&product][&discount][&travelClass][&passing][&travelRequestType][&disabledTransportModalities][&firstMileModality][&lastMileModality][&entireTripModality]".
5. If the origin or destination cannot be determined, include a polite error message in the "message" field indicating that more information is needed.
6. Provide a suitable title for the conversation, including the starting or ending point if available.

Summary:
- Extract the origin and destination from the user\'s message. If not provided or if "current location" is requested, use "current location" for the respective point.
- Always include GPS coordinates in the parameters [&originLat], [&originLng] or [&destinationLat], [&destinationLng] if using the current location.
- Include the city name for metro stations like "Blaak" or "Beurs".
- Place a message for the user under "message".
- Fill in other possible parameters.
- Provide a title for the conversation with the starting or ending point if available.
- If no useful information is found, respond with a JSON containing a polite error message in the "message" field.

Always respond in JSON format, even in case of an error!

Here is the exact JSON format. Ensure these five fields are always present, and do not change the names of these fields:
{
   "origin": "origin",
   "destination": "destination",
   "message": "Your message to the user",
   "parameters": "Any additional parameters found",
   "title": "The title of the conversation with the locations."
}

Here is the user\'s current location (if needed):
' . $data->location . '

Here is the user\'s message:
' . $data->message;


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
//        return response()->json($json_response);
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
