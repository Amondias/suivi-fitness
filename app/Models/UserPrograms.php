<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Programs;

class UserPrograms extends Model
{
    protected $table = 'user_programs';

    protected $fillable = [
        'user_id',
        'program_id',
        'started_at',
        'status',
    ];

    protected $casts = [
        'started_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Programs::class, 'program_id');
    }
}
