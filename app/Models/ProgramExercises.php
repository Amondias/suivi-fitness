<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Programs;
use App\Models\Exercise;

class ProgramExercises extends Model
{
    protected $table = 'program_exercises';

    protected $fillable = [
        'program_id',
        'exercise_id',
        'sets',
        'reps',
        'rest_seconds',
        'order',
        'day_of_week',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Programs::class, 'program_id');
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class, 'exercise_id');
    }
}
