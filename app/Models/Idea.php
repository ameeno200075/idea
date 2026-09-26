<?php

namespace App\Models;

use App\IdeaStatus;
use Database\Factories\IdeaFactory;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Idea extends Model
{
    /** @use HasFactory<IdeaFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'image',
        'links',
    ];

    protected $casts = [
        'links' => AsArrayObject::class,
        'status' => IdeaStatus::class,
    ];

    protected $attributes = [
        'status' => IdeaStatus::PENDING->value,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function steps()
    {
        return $this->hasMany(Step::class);
    }

    public static function statusCounts(User $user): Collection
    {
        $counts = $user->ideas()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return collect(IdeaStatus::cases())
            ->mapWithKeys(fn ($status) => [
                $status->value => $counts->get($status->value, 0),
            ])
            ->put('all', $user->ideas()->count());
    }
}
