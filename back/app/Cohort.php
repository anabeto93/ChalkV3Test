<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cohort extends Model
{
    /**
     * The Institution the Cohort belongs to.
     */
    public function institution() {
        return $this->belongsTo(Institution::class);
    }

    /**
     * The Courses that belong to the Cohort.
     */
    public function courses() {
        return $this->belongsToMany(Course::class);
    }

    /**
     * The Users that belong to the Cohort.
     */
    public function users() {
        return $this->belongsToMany(User::class);
    }
}
