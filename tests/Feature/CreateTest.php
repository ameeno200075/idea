<?php

use App\Models\User;

it('create a new idea', function () {
    $this->actingAs($user = User::factory()->create());

    $this->post(route('ideas.store'), [
        'title' => 'My First Idea',
        'description' => 'This is my first Idea Description.',
        'status' => 'completed',
        'links' => ['https://example.com', 'https://laravel.com'],
    ])->assertRedirectToRoute('ideas.index');

    expect($user->ideas()->first())->toMatchArray([
        'title' => 'My First Idea',
        'description' => 'This is my first Idea Description.',
        'status' => 'completed',
        'links' => ['https://example.com', 'https://laravel.com'],
    ]);
});
