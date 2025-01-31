<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GroqApiService
{
    protected $client;
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.groq.api_key');
        $this->apiUrl = 'https://api.groq.com/openai/v1/chat/completions';
    }

    public function sendRequest(array $inputData)
    {
        $payload = [
            "model" => "llama-3.3-70b-versatile",
            "temperature" => 0.2,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $inputData['prompt'],
                ],
            ],
        ];

        try {
            $response = $this->client->post($this->apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new Exception('API request failed with status code ' . $response->getStatusCode());
            }

            $responseBody = json_decode($response->getBody(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('JSON decode error: ' . json_last_error_msg());
            }

            if (isset($responseBody['error'])) {
                throw new Exception('API error: ' . $responseBody['error']['message']);
            }

            return $responseBody;

        } catch (RequestException $e) {
            throw new Exception('HTTP request failed: ' . $e->getMessage());
        }
    }
}
