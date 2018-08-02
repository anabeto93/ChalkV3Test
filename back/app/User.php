<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The Institution the User belongs to.
     */
    public function institution() {
        return $this->belongsTo(Institution::class);
    }

    /**
     * The Courses that belong to the User.
     */
    public function courses() {
        return $this->belongsToMany(Course::class);
    }

    /**
     * The Cohorts that belong to the User.
     */
    public function cohorts() {
        return $this->belongsToMany(Cohort::class);
    }

    /**
     * The Progressions that belong to the User.
     */
    public function progressions() {
        return $this->hasMany(Progression::class);
    }

    /**
     * The QuizResults that belong to the User.
     */
    public function quizResults() {
        return $this->hasMany(QuizResult::class);
    }

    /**
     * Get User Institution
     *
     * @return Model|\Illuminate\Database\Eloquent\Relations\BelongsTo|null|object
     */
    public function getInstitution()
    {
        return $this->institution()->first();
    }

    /**
     * Get User Cohorts
     *
     * @return mixed
     */
    public function getCohorts()
    {
        return $this->getInstitution()->cohorts()->get();
    }
}
