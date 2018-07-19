<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    /**
     * The Admins that belong to the Institution.
     */
    public function admins() {
        return $this->belongsToMany(Admin::class);
    }

    /**
     * The Users that belong to the Institution.
     */
    public function users() {
        return $this->hasMany(User::class);
    }

    /**
     * The Courses that belong to the Institution.
     */
    public function courses() {
        return $this->hasMany(Course::class);
    }

    /**
     * The Cohorts that belong to the Institution.
     */
    public function cohorts() {
        return $this->hasMany(Cohort::class);
    }
}
