<?php

use App\Models\Idea;
use App\Models\User;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

it('analyzes an idea using only the approved idea context', function () {
    config([
        'ai.provider' => 'gemini',
        'ai.api_key' => 'test-key',
    ]);

    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [[
                'content' => [
                    'parts' => [[
                        'text' => json_encode([
                            'summary' => 'A promising idea.',
                            'strengths' => ['Clear problem'],
                            'risks' => ['Needs validation'],
                            'next_steps' => ['Interview users'],
                        ]),
                    ]],
                ],
            ]],
        ]),
    ]);

    $this->actingAs($user = User::factory()->create());
    $idea = Idea::factory()->for($user)->create([
        'title' => 'A private idea',
        'description' => 'A useful description',
        'status' => 'pending',
        'image' => 'private-image.jpg',
        'links' => ['https://example.com'],
    ]);

    $this->postJson(route('ideas.analyze', $idea))
        ->assertOk()
        ->assertExactJson([
            'analysis' => [
                'summary' => 'A promising idea.',
                'strengths' => ['Clear problem'],
                'risks' => ['Needs validation'],
                'next_steps' => ['Interview users'],
            ],
        ]);

    Http::assertSent(function (Request $request) {
        $body = $request->data();
        $message = $body['contents'][0]['parts'][0]['text'];
        $context = json_decode(
            str($message)->after("<idea_context>\n")->before("\n</idea_context>")->toString(),
            true
        );

        return $context === [
            'title' => 'A private idea',
            'description' => 'A useful description',
            'status' => 'pending',
        ];
    });
});

it('forbids analyzing another users idea', function () {
    $this->actingAs(User::factory()->create());
    $idea = Idea::factory()->create();

    $this->postJson(route('ideas.analyze', $idea))
        ->assertForbidden();
});

it('returns a safe error when Gemini returns malformed analysis JSON', function () {
    config([
        'ai.provider' => 'gemini',
        'ai.api_key' => 'test-key',
    ]);

    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [[
                'content' => [
                    'parts' => [['text' => '{"summary": "incomplete"}']],
                ],
            ]],
        ]),
    ]);

    $this->actingAs($user = User::factory()->create());
    $idea = Idea::factory()->for($user)->create();

    $this->postJson(route('ideas.analyze', $idea))
        ->assertStatus(502)
        ->assertExactJson([
            'message' => 'The assistant is temporarily unavailable. Please try again shortly.',
        ]);
});
