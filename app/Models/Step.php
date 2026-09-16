<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    /** @use HasFactory<\Database\Factories\StepFactory> */
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
