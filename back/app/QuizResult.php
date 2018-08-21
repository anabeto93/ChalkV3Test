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
     * The User the QuizResult belongs to.
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * The Grade in Percent
     */
    public function getGradePercentAttribute() {
        return round((float)$this->grade * 100);
    }
}
