<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ExerciseCategories;
use App\Models\ProgramExercises;

class Exercise extends Model
{
    protected $table = 'exercises';

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'difficulty',
        'muscle_group',
        'equipment',
        'video_url',
        'image',
        'created_by',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExerciseCategories::class, 'category_id');
    }

    public function programExercises(): HasMany
    {
        return $this->hasMany(ProgramExercises::class, 'exercise_id');
    }
}
