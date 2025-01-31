<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Exception;

class OpenRouterService
{
    protected $client;
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.openrouter.api_key');
        $this->apiUrl = 'https://openrouter.ai/api/v1/chat/completions';
    }

    public function sendRequest(array $inputData)
    {
        $payload = [
            'model' => 'google/gemini-2.0-flash-exp:free',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a helpful assistant. Output only the final result in JSON array format. Do not explain.',
                ],
                [
                    'role' => 'user',
                    'content' => $inputData['prompt'],
                ],
            ],
            'max_tokens' => 200,
            'temperature' => 0.7,
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
