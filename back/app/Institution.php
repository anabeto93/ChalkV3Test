<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
<<<<<<< HEAD
    private $name;
=======
>>>>>>> feature/institutionController

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

<<<<<<< HEAD
    public function store(string $name)
    {
        $this->name = $name;
        try {
            $this->save();
            return [
                'status' => 'success',
                'code' => 200,
                'reason' => 'Institution created!'
            ];
        } catch (\Exception $exception) {
            return [
                'error' => true,
                'code' => $exception->getCode(),
                'reason' => $exception->getMessage()
            ];
        }
    }

    /**
     * Get Users that belong to Institution
     *
     * @return array
     */
    public function getUsers()
    {
        return $this->users()->get()->toArray();
    }

    /**
     * Get Cohorts that belong to Institution
     *
     * @return array
     */
    public function getCohorts()
    {
        return $this->cohorts()->get()->toArray();
    }

    /**
     * Get Courses that belong to Institution
     *
     * @return array
     */
    public function getCourses()
    {
        return $this->courses()->get()->toArray();
    }
=======
>>>>>>> feature/institutionController
}
