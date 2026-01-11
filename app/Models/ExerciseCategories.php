<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Exercise;

class ExerciseCategories extends Model
{
    protected $table = 'exercise_categories';

    protected $fillable = [
        'name',
        'description',
        'icon',
    ];

    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class, 'category_id');
    }
}
