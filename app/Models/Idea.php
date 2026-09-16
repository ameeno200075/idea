<?php

namespace App\Models;

use App\IdeaStatus;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Idea extends Model
{
    /** @use HasFactory<\Database\Factories\IdeaFactory> */
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
        'status' => IdeaStatus::PENDING,
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function steps()
    {
        return $this->hasMany(Step::class);
    }
}
