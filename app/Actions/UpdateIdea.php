<?php

namespace App\Actions;

use App\Models\Idea;
use Illuminate\Support\Facades\DB;

class UpdateIdea
{
    /**
     * Update the specified idea.
     */
    public function handle(array $attributes, Idea $idea): void
    {
        $data = collect($attributes)->only([
            'title',
            'description',
            'status',
            'links',
        ])->toArray();

        if ($attributes['image'] ?? false) {
            $data['image'] = $attributes['image']->store('ideas', 'public');
        }

        DB::transaction(function () use ($data, $attributes, $idea) {
            $idea->update($data);

            $idea->steps()->delete();

            $steps = collect($attributes['steps'] ?? [])
                ->map(fn (array $step) => [
                    'description' => $step['description'],
                    'completed' => (bool) ($step['completed'] ?? false),
                ]);

            $idea->steps()->createMany($steps->all());
        });
    }
}
