<?php

namespace App\Models;

use Database\Factories\StepFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    /** @use HasFactory<StepFactory> */
    use HasFactory;

    protected $fillable = [
        'idea_id',
        'description',
        'completed',
    ];

    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }
}
