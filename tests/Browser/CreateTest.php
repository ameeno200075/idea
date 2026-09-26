<?php

use App\Models\Idea;
use App\Models\User;

it('create a new idea', function () {
    $this->actingAs($user = User::factory()->create());

    visit('/ideas')
        ->click('What\'s the idea?')
        ->fill('title', 'My First Idea')
        ->fill('description', 'This is my first Idea Description.')
        ->fill('@input-new-link', 'https://example.com')
        ->click('@button-add-link')
        ->fill('@input-new-link', 'https://laravel.com')
        ->click('@button-add-link')
        ->click('@button-status-completed')

        ->press('Create Idea')
        ->assertPathIs('/ideas');

    expect(Idea::first())->toMatchArray([
        'title' => 'My First Idea',
        'description' => 'This is my first Idea Description.',
        'status' => 'completed',
        'links' => ['https://example.com', 'https://laravel.com'],
    ]);
});
