<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
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
