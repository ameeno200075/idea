<?php

use App\Models\Idea;
use App\Models\User;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

it('provides only the authenticated users ideas to the chat assistant', function () {
    config([
        'ai.provider' => 'gemini',
        'ai.api_key' => 'test-key',
    ]);

    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [[
                'content' => [
                    'parts' => [[
                        'text' => 'You have one idea: Build a personal assistant (pending).',
                    ]],
                ],
            ]],
        ]),
    ]);

    $user = User::factory()->create();
    $idea = Idea::factory()->for($user)->create([
        'title' => 'Build a personal assistant',
        'description' => 'Help me choose what to work on next.',
        'status' => 'pending',
    ]);
    Idea::factory()->create([
        'title' => 'Another users private idea',
        'description' => 'This must never be shared.',
    ]);

    $this->actingAs($user)
        ->postJson(route('assistant.chat'), ['message' => 'What ideas do I have?'])
        ->assertOk()
        ->assertExactJson([
            'message' => 'You have one idea: Build a personal assistant (pending).',
        ]);

    Http::assertSent(function (Request $request) use ($idea) {
        $message = $request->data()['contents'][0]['parts'][0]['text'];
        $context = json_decode(
            str($message)->after("<idea_context>\n")->before("\n</idea_context>")->toString(),
            true
        );

        return $context === [[
            'id' => $idea->id,
            'title' => 'Build a personal assistant',
            'description' => 'Help me choose what to work on next.',
            'status' => 'pending',
            'created_at' => $idea->created_at->toISOString(),
        ]]
            && ! str($message)->contains('Another users private idea');
    });
});

it('provides an empty context when the user has no ideas', function () {
    config([
        'ai.provider' => 'gemini',
        'ai.api_key' => 'test-key',
    ]);

    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [[
                'content' => [
                    'parts' => [['text' => 'You currently have no Ideas available.']],
                ],
            ]],
        ]),
    ]);

    $this->actingAs(User::factory()->create())
        ->postJson(route('assistant.chat'), ['message' => 'What ideas do I have?'])
        ->assertOk();

    Http::assertSent(function (Request $request) {
        return str($request->data()['contents'][0]['parts'][0]['text'])
            ->contains("<idea_context>\n[]\n</idea_context>");
    });
});

it('retries a temporarily unavailable Gemini request', function () {
    config([
        'ai.provider' => 'gemini',
        'ai.api_key' => 'test-key',
    ]);

    Http::fakeSequence('generativelanguage.googleapis.com/*')
        ->push(['error' => ['status' => 'UNAVAILABLE']], 503)
        ->push([
            'candidates' => [[
                'content' => [
                    'parts' => [['text' => 'Retry succeeded.']],
                ],
            ]],
        ]);

    $this->actingAs(User::factory()->create())
        ->postJson(route('assistant.chat'), ['message' => 'What ideas do I have?'])
        ->assertOk()
        ->assertExactJson(['message' => 'Retry succeeded.']);

    Http::assertSentCount(2);
});
