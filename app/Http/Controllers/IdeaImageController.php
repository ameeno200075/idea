<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class IdeaImageController extends Controller
{
    public function destroy(Idea $idea)
    {
        Gate::authorize('workWith', $idea);

        if ($idea->image) {
            Storage::disk('public')->delete($idea->image);
        }

        $idea->update(['image' => null]);

        return back();
    }
}
