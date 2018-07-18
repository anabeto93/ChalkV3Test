<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionAnswer extends Model
{
    /**
     * The Question the QuestionAnswer belongs to.
     */
    public function question() {
        return $this->belongsTo(Question::class);
    }
}
