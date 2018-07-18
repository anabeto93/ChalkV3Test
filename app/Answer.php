<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    /**
     * The Question the Answer belongs to.
     */
    public function question() {
        return $this->belongsTo(Question::class);
    }
}
