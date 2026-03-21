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
                        'content' => 'Give me a random greeting and tell me the time and weather in the UK',
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

    public function playground()
    {
        return view('playground');
    }

    public function playgroundSubmit(Request $request)
    {
        try {
            $prompt = $request->input('prompt');

            if (empty($prompt)) {
                return response()->json(['error' => 'Prompt is required.'], 422);
            }

            $apiKey = env('ANTHROPIC_API_KEY');

            if (empty($apiKey)) {
                return response()->json(['error' => 'ANTHROPIC_API_KEY not configured.'], 500);
            }

            $bladePath = resource_path('views/playground.blade.php');
            $currentFile = file_get_contents($bladePath);

            $systemPrompt = <<<'SYSTEM'
You are a UI builder that modifies a Laravel Blade template file. The user will describe UI changes they want. You respond with ONLY the complete updated Blade file — no markdown, no explanations, no code fences, no confirmation questions. Just output the full file content ready to be written to disk.

Rules:
- Output the COMPLETE updated file content. It will be written directly to the blade file.
- You MUST preserve the prompt form (#prompt-form), output div (#output), the CSRF meta tag, and the JavaScript that handles form submission. These are the controls the user interacts with — never remove or break them.
- Apply the requested UI changes to the HTML and CSS in the file.
- Never ask for clarification. Make reasonable assumptions and just do it.
- Do not wrap your output in code fences or backticks. Output raw file content only.
SYSTEM;

            $userMessage = "Current file content:\n{$currentFile}\n\nRequested change: {$prompt}";

            $client = new Client(apiKey: $apiKey);

            $response = $client->messages->create(
                maxTokens: 8192,
                system: $systemPrompt,
                messages: [
                    [
                        'role' => 'user',
                        'content' => $userMessage,
                    ],
                ],
                model: 'claude-sonnet-4-5-20250929',
            );

            $newContent = $response->content[0]->text ?? '';

            if (empty($newContent)) {
                return response()->json(['error' => 'Empty response from Claude.'], 500);
            }

            file_put_contents($bladePath, $newContent);

            return response()->json(['response' => 'Changes applied. Refresh the page to see them.']);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
