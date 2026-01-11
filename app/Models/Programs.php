<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\ProgramExercises;
use App\Models\UserPrograms;

class Programs extends Model
{
    protected $table = 'programs';

    protected $fillable = [
        'name',
        'description',
        'coach_id',
        'difficulty',
        'duration_weeks',
        'goal',
        'image',
        'is_public',
    ];

    protected $casts = [
        'duration_weeks' => 'integer',
        'is_public' => 'boolean',
    ];

    public function coach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function programExercises(): HasMany
    {
        return $this->hasMany(ProgramExercises::class, 'program_id');
    }

    public function userPrograms(): HasMany
    {
        return $this->hasMany(UserPrograms::class, 'program_id');
    }
}
