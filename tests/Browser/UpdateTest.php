
<?php

use App\Models\Idea;
use App\Models\User;

it('updates an existing idea', function () {
    $this->actingAs($user = User::factory()->create());

    $idea = Idea::factory()->create([
        'user_id' => $user->id,
        'title' => 'Old Idea Title',
        'description' => 'Old idea description.',
        'status' => 'pending',
        'links' => ['https://example.com'],
    ]);

    visit("/ideas/{$idea->id}")
        ->click('Edit')
        ->fill('title', 'Updated Idea Title')
        ->fill('description', 'Updated idea description.')
        ->click('@button-status-completed')
        ->press('Update Idea')
        ->assertPathIs("/ideas/{$idea->id}");

    expect($idea->fresh())->toMatchArray([
        'title' => 'Updated Idea Title',
        'description' => 'Updated idea description.',
        'status' => 'completed',
        'links' => ['https://example.com'],
    ]);
});
