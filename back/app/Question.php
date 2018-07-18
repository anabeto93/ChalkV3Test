<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /**
     * The Quiz the Question belongs to.
     */
    public function quiz() {
        return $this->belongsTo(Quiz::class);
    }
}
