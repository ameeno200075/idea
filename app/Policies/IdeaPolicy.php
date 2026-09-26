<?php

namespace App\Policies;

use App\Models\Idea;
use App\Models\User;

class IdeaPolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    public function workWith(User $user, Idea $idea): bool
    {
        return $user->id === $idea->user_id;
    }
}
