<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CreateIdea
{
    public function handle(array $attributes, User $user): void
    {
        
        $imagePath = null;

        try {
            $data = collect($attributes)->only([
                'title',
                'description',
                'status',
                'links',
            ])->toArray();

            if (isset($attributes['image'])) {
                $imagePath = $attributes['image']->store('ideas', 'public');
                $data['image'] = $imagePath;
            }

            DB::transaction(function () use ($data, $attributes, $user) {
                $idea = $user->ideas()->create($data);

                $steps = collect($attributes['steps'] ?? [])
                    ->map(fn (array $step) => [
                        'description' => $step['description'],
                        'completed' => (bool) ($step['completed'] ?? false),
                    ]);

                $idea->steps()->createMany($steps);
            });
        } catch (\Throwable $exception) {
            if ($imagePath !== null) {
                Storage::disk('public')->delete($imagePath);
            }

            throw $exception;
        }
    }
}
