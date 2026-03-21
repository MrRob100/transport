<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Anthropic\Client;

class TestController extends Controller
{
    public function test()
    {
        try {
            $apiKey = env('ANTHROPIC_API_KEY');

            if (empty($apiKey)) {
                return response("Error: ANTHROPIC_API_KEY not configured in .env file", 500)
                    ->header('Content-Type', 'text/plain');
            }

            $client = new Client(apiKey: $apiKey);

            $response = $client->messages->create(
                maxTokens: 1024,
                messages: [
                    [
                        'role' => 'user',
                        'content' => 'Give me a random greeting',
                    ],
                ],
                model: 'claude-sonnet-4-5-20250929',
            );

            $greeting = $response->content[0]->text ?? 'No response received';

            return response("Claude API Response:\n\n" . $greeting, 200)
                ->header('Content-Type', 'text/plain');

        } catch (\Exception $e) {
            return response("Exception: " . $e->getMessage(), 500)
                ->header('Content-Type', 'text/plain');
        }
    }
}
