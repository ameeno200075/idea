<?php

namespace App\Actions;

use App\Models\Idea;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateIdea
{
    /**
     * Update the specified idea.
     */
    public function handle(array $attributes, Idea $idea): void
    {
        $imagePath = null;
        $oldImagePath = $idea->image;

        try {
            $data = collect($attributes)->only([
                'title',
                'description',
                'status',
                'links',
            ])->toArray();

            if ($attributes['image'] ?? false) {
                $imagePath = $attributes['image']->store('ideas', 'public');
                $data['image'] = $imagePath;
            }

            DB::transaction(function () use ($data, $attributes, $idea) {
                $idea->update($data);

                $idea->steps()->delete();

                $steps = collect($attributes['steps'] ?? [])
                    ->map(fn (array $step) => [
                        'description' => $step['description'] ?? '',
                        'completed' => (bool) ($step['completed'] ?? false),
                    ]);

                $idea->steps()->createMany($steps->all());
            });

            if ($imagePath !== null && $oldImagePath !== null) {
                Storage::disk('public')->delete($oldImagePath);
            }
        } catch (\Throwable $exception) {
            if ($imagePath !== null) {
                Storage::disk('public')->delete($imagePath);
            }

            throw $exception;
        }
    }
}
