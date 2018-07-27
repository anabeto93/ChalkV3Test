<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    const DEFAULT_FOLDER = 'Default Folder';
    /**
     * The Course the Folder belongs to.
     */
    public function course() {
        return $this->belongsTo(Course::class);
    }

    /**
     * The Sessions that belongs to the Folder.
     */
    public function sessions() {
        return $this->hasMany(Session::class);
    }

    /**
     * The Quizzes that belongs to the Folder.
     */
    public function quizzes() {
        return $this->morphMany(Quiz::class, 'quizzable');
    }
}
