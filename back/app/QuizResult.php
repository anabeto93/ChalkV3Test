<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    /**
     * The Quiz the QuizResult belongs to.
     */
    public function quiz() {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * The Student the QuizResult belongs to.
     */
    public function student() {
        return $this->belongsTo(Student::class);
    }
}
