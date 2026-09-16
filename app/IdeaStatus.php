<?php

namespace App;

enum IdeaStatus: string
{
    case PENDING = 'pending';

    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'pending',
            self::IN_PROGRESS => 'in_progress',
            self::COMPLETED => 'completed',
        };
    }
}
