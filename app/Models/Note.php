<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Note extends Model
{
    protected $fillable = [
        'username',
        'title',
        'content',
        'document',
        'summary',
    ];

    /**
     * Get the quizzes for this note.
     */
    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * Get the latest quiz for this note.
     */
    public function latestQuiz()
    {
        return $this->hasOne(Quiz::class)->latestOfMany();
    }
}
