<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class ChatController extends Controller
{
    protected $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . config('AZURE_OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function askToChatGpt($message)
    {
        try {
            $response = $this->httpClient->post('chat/completions', [
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are'],
                        ['role' => 'user', 'content' => $message],
                    ],
                ],
            ]);

            return json_decode($response->getBody(), true)['choices'][0]['message']['content'];
        } catch (GuzzleException $e) {
            // Log or handle the error
            \Log::error('Error in askToChatGpt: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function submitMessage()
    {
        try {
            // Get the JSON data from the request body
            $json = file_get_contents('php://input');

            // Decode the JSON data
            $data = json_decode($json);

            // Ask ChatGPT for a response
            $chatResponse = $this->askToChatGpt($data->message);

            // Prepare the response
            $response = [
                'message' => $chatResponse,
                'data' => null,
            ];

            // Encode the response as JSON
            return response()->json($response);
        } catch (\Exception $e) {
            // Log or handle the error
            \Log::error('Error in submitMessage: ' . $e->getMessage());
            return response()->json(['message' => 'Sorry, there was an error processing your request.'], 500);
        }
    }
}
