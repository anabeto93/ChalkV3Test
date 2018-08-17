<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function setQuizId(int $quiz)
    {
        $this->quiz()->associate($quiz);
        return $this;
    }

    public function setType(string $type)
    {
        $this->attributes['type'] = $type;
        return $this;
    }

    public function setFeedback(string $feedback)
    {
        $this->attributes['feedback'] = $feedback;
        return $this;
    }

    public function setTitle(string $title)
    {
        $this->attributes['title'] = $title;
        return $this;
    }
    /**
     * The Quiz the Question belongs to.
     */
    public function quiz() {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * The Answers that belong to the Question.
     */
    public function questionAnswers() {
        return $this->hasMany(QuestionAnswer::class);
    }
}
