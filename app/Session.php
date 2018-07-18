<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    /**
     * The Course the Session belongs to.
     */
    public function course() {
        return $this->belongsTo(Course::class);
    }

    /**
     * The Folder (nullable) the Session belongs to.
     */
    public function folder() {
        return $this->belongsTo(Folder::class);
    }

    /**
     * The Progressions that belong to the Session.
     */
    public function progressions() {
        return $this->hasMany(Progression::class);
    }

    /**
     * The Quizzes that belongs to the Session.
     */
    public function quizzes() {
        return $this->morphMany(Quiz::class, 'quizzable');
    }

    /**
     * The Files that belongs to the Session.
     */
    public function files() {
        return $this->morphMany(File::class, 'fileable');
    }
}
