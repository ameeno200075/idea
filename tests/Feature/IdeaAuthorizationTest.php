<?php

use App\Models\Idea;
use App\Models\Step;
use App\Models\User;

it('creates an idea through the HTTP endpoint', function () {
    $this->actingAs($user = User::factory()->create());

    $this->post(route('ideas.store'), [
        'title' => 'A test idea',
        'description' => 'A test description.',
        'status' => 'pending',
    ])->assertRedirectToRoute('ideas.index');

    expect($user->ideas()->where('title', 'A test idea')->exists())->toBeTrue();
});

it('prevents modifying another users idea', function () {
    $this->actingAs(User::factory()->create());
    $idea = Idea::factory()->create();

    $this->patch(route('ideas.update', $idea), [
        'title' => 'Attempted update',
        'description' => 'This should not be saved.',
        'status' => 'pending',
    ])->assertForbidden();

    expect($idea->fresh()->title)->not->toBe('Attempted update');
});

it('prevents deleting another users idea', function () {
    $this->actingAs(User::factory()->create());
    $idea = Idea::factory()->create();

    $this->delete(route('ideas.destroy', $idea))
        ->assertForbidden();

    expect(Idea::find($idea->id))->not->toBeNull();
});

it('prevents completing another users step', function () {
    $this->actingAs(User::factory()->create());
    $step = Step::factory()->create();

    $this->patch(route('steps.update', $step))
        ->assertForbidden();

    expect((bool) $step->fresh()->completed)->toBeFalse();
});
