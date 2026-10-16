<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendChatMessageRequest;
use App\Models\Idea;
use App\Services\AiChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use RuntimeException;

class AiChatController extends Controller
{
    public function __construct(private readonly AiChatService $chatService) {}

    public function index(): View
    {
        return view('assistant.chat');
    }

    public function chat(SendChatMessageRequest $request): JsonResponse
    {
        try {
            $ideas = $request->user()
                ->ideas()
                ->get(['id', 'title', 'description', 'status', 'created_at'])
                ->map(fn (Idea $idea) => [
                    'id' => $idea->id,
                    'title' => $idea->title,
                    'description' => $idea->description,
                    'status' => $idea->status->value,
                    'created_at' => $idea->created_at->toISOString(),
                ])
                ->all();

            return response()->json([
                'message' => $this->chatService->reply(
                    $request->validated('message'),
                    $ideas
                ),
            ]);
        } catch (RuntimeException $exception) {
            report($exception);

            return response()->json([
                'message' => 'The assistant is temporarily unavailable. Please try again shortly.',
            ], 502);
        }
    }

    public function analyze(Idea $idea): JsonResponse
    {
        Gate::authorize('workWith', $idea);

        try {
            return response()->json([
                'analysis' => $this->chatService->analyzeIdea([
                    'title' => $idea->title,
                    'description' => $idea->description,
                    'status' => $idea->status->value,
                ]),
            ]);
        } catch (RuntimeException $exception) {
            report($exception);

            return response()->json([
                'message' => 'The assistant is temporarily unavailable. Please try again shortly.',
            ], 502);
        }
    }
}
