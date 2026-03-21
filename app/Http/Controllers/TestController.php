<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Anthropic\Anthropic;

class TestController extends Controller
{
    public function test()
    {
        try {
            // Get API key from environment
            $apiKey = env('ANTHROPIC_API_KEY');

            if (empty($apiKey)) {
                return response("Error: ANTHROPIC_API_KEY not configured in .env file", 500)
                    ->header('Content-Type', 'text/plain');
            }

            // Initialize Anthropic client
            $client = Anthropic::factory()
                ->withApiKey($apiKey)
                ->make();

            // Create a message request
            $response = $client->messages()->create([
                'model' => 'claude-sonnet-4-5-20250929',
                'max_tokens' => 1024,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => 'Give me a random greeting',
                    ],
                ],
            ]);

            // Extract the text content from the response
            $greeting = $response->content[0]->text ?? 'No response received';

            return response("Claude API Response:\n\n" . $greeting, 200)
                ->header('Content-Type', 'text/plain');

        } catch (\Exception $e) {
            return response("Exception: " . $e->getMessage(), 500)
                ->header('Content-Type', 'text/plain');
        }
    }
}
