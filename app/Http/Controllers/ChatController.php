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
        if(Auth::user()) {
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

    public function viewMap()
    {
        return view('app.map');
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
Je bent nu een reisassistent AI. Het is jouw taak om een JSON bestand te maken voor de gebruiker met daarin benodigde gegevens.

Je moet in het bericht van de gebruiker een begin en eindpunt van zijn reis halen. Deze moet je als eerste in het JSON bestand zetten onder "origin" en "destination".
Daarna moet je een bericht voor de gebruiker achterlaten, bijvoorbeeld door hem of haar een fijne rit te wensen, begin het bericht met: "hier is uw route van _ naar _", geef je eigen draai aan dit bericht. Zet deze in het derde veld van de JSON, namelijk het "message" veld.
Als laatste moet je kijken of er in het bericht nog dingen staan waar je parameters van kunt maken, hier is een lijst van alle parameters:
"[?lang][&fromStation][&originUicCode][&originLat][&originLng][&originName][&toStation][&destinationUicCode][&destinationLat][&destinationLng][&destinationName][&viaStation][&viaUicCode][&viaLat][&viaLng][&originWalk][&originBike][&originCar][&destinationWalk][&destinationBike][&destinationCar][&dateTime][&searchForArrival][&departure][&context][&shorterChange][&addChangeTime][&minimalChangeTime][&viaWaitTime][&originAccessible][&travelAssistance][&nsr][&travelAssistanceTransferTime][&accessibilityEquipment1][&accessibilityEquipment2][&searchForAccessibleTrip][&filterTransportMode][&localTrainsOnly][&excludeHighSpeedTrains][&excludeTrainsWithReservationRequired][&product][&discount][&travelClass][&passing][&travelRequestType][&disabledTransportModalities][&firstMileModality][&lastMileModality][&entireTripModality]".
Als je parameters in het bericht van de gebruiker kan vinden moet je die in het "parameters" veld van de JSON zetten.
Het eerste wat je moet vinden is de longitude en latitude van het begin en eindpunt van de gebruiker.
Geef het gesprek een titel.


Als je tegen een error aanloopt tijdens het vinden van een begin en eindpunt, laat dan een bericht achter in "message" en laat de andere JSON dingen zoals "destination, parameters en origin" leeg. geef een technische uitleg in het "error" veld.

Samengevat:
1. Haal een begin en eindpunt uit een bericht van de gebruiker.
2. Als er een error is, geef dan een foutmelding en hulp in "message" en een technische uitleg in "error". Laat de andere velden leeg.
3. Laat een bericht achter in "message"
4. Kijk of er andere parameters ingevuld kunnen worden.
5. Geef het gesprek een passende titel met het start of eindpunt.

Je moet je antwoord in een JSON formaat sturen, ook tijdens een error!

Hier is het exacte formaat van de JSON, zorg dat je altijd deze vier velden hebt en verander nooit de namen van deze velden.
{
   "origin": "origin",
   "destination": "destination",
   "message": "your message to the customer",
   "parameters": "additional parameters you can find",
   "error": "The optional error you had while getting an start and endpoint.",
   "title": "The title of the conversation, include at least one of the locations.",
}

Stuur nooit iets zonder dat het in een JSON formaat is!

Hier is het bericht van de gebruiker:' .
            $data->message;

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
