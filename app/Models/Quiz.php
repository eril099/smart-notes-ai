<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quiz extends Model
{
    protected $fillable = [
        'note_id',
        'questions',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'questions' => 'array',
        ];
    }

    /**
     * Get the note that owns this quiz.
     */
    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }
}
