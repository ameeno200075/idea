<?php

use App\Models\Idea;
use App\Models\User;

it('requires authentication', function () {

    $idea = Idea::factory()->create();
    $this->get(route('ideas.show', $idea))
        ->assertRedirectToRoute('login');
});

it('disallow accessesing an idea you didnt create', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $idea = Idea::factory()->create();
    $this->get(route('ideas.show', $idea))->assertForbidden();
});
