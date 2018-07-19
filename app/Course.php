<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /**
     * The Institution the Course belongs to.
     */
    public function institution() {
        return $this->belongsTo(Institution::class);
    }

    /**
     * The Folders that belong to the Course.
     */
    public function folders() {
        return $this->hasMany(Folder::class);
    }

    /**
     * The Sessions that belong to the Course.
     */
    public function sessions() {
        return $this->hasMany(Session::class);
    }

    /**
     * The Users that belong to the Course.
     */
    public function users() {
        return $this->belongsToMany(User::class);
    }

    /**
     * The Cohorts that belong to the Course.
     */
    public function cohorts() {
        return $this->belongsToMany(Cohort::class);
    }

    /**
     * The Quizzes that belongs to the Course.
     */
    public function quizzes() {
        return $this->morphMany(Quiz::class, 'quizzable');
    }
}
