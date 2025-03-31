<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'created_date',
        'completion_date',
        'is_completed',
        'user_id'
    ];

    protected $casts = [
        'created_date' => 'datetime',
        'completion_date' => 'datetime',
        'is_completed' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}