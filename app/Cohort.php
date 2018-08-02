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

    /**
     * Get all user for cohort
     *
     * @return array
     */
    public function getUsers(): array
    {
        return $this->users()->get()->toArray();
    }

    /**
     * Get all Courses for Cohort
     *
     * @return array
     */
    public function getCourses(): array
    {
        return $this->courses()->get()->toArray();
    }

    /**
     * Get Institution for Cohort
     *
     * @return array
     */
    public function getInstitution(): array
    {
        return $this->institution()->first()->toArray();
    }
}
