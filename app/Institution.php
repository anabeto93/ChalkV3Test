<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{

    private $name;

    public function __construct(string $name = null)
    {
        $this->name = $name;
    }

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

    public function store()
    {
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

    public function getUsers()
    {
        return $this->users()->get();
    }
}
