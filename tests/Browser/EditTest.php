
<?php

use App\Models\Idea;
use App\Models\User;

it('updates an existing idea', function () {
    $this->actingAs($user = User::factory()->create());

    $idea = Idea::factory()->create([
        'user_id' => $user->id,
        'title' => 'My First Idea',
        'description' => 'This is my first Idea Description.',
        'status' => 'pending',
        'links' => ['https://example.com'],
    ]);

    visit("/ideas/{$idea->id}")
        // Use your actual edit button/link selector here
        ->click('Edit')
        ->fill('title', 'My Updated Idea')
        ->fill('description', 'This is my updated Idea Description.')
        ->click('@button-status-completed')
        ->press('Update Idea')
        ->assertPathIs("/ideas/{$idea->id}");

    expect($idea->fresh())->toMatchArray([
        'title' => 'My Updated Idea',
        'description' => 'This is my updated Idea Description.',
        'status' => 'completed',
        'links' => ['https://example.com'],
    ]);
});
