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
     * The Students that belong to the Institution.
     */
    public function students() {
        return $this->hasMany(Student::class);
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
