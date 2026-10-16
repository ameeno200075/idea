<?php

use App\Models\User;

it('rate limits AI requests for an authenticated user', function () {
    $this->actingAs(User::factory()->create());

    foreach (range(1, 10) as $attempt) {
        $this->postJson(route('assistant.chat'), ['message' => 'Help me prioritize my ideas.'])
            ->assertStatus(502);
    }

    $this->postJson(route('assistant.chat'), ['message' => 'Help me prioritize my ideas.'])
        ->assertTooManyRequests();
});
