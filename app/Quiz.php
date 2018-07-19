<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    /**
     * The Institution the Quiz belongs to.
     */
    public function institution() {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Get all of the owning Quiz models.
     */
    public function quizzable()
    {
        return $this->morphTo();
    }

    /**
     * The Questions that belong to the Quiz.
     */
    public function questions() {
        return $this->hasMany(Question::class);
    }

    /**
     * The Quiz the QuizResult belongs to.
     */
    public function quizResults() {
        return $this->hasMany(QuizResult::class);
    }
}
