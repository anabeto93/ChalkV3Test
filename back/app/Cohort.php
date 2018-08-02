<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    public function setName($name)
    {
        $this->attributes['name'] = ucwords(strtolower($name));
    }

    public function setInstitutionId()
    {
        $this->attributes['institution_id'] = Auth::user()->getInstitution()->id;
    }

    public function store(Cohort $cohort, string $name)
    {
        $cohort->setName($name);
        $cohort->setInstitutionId();
        try {
            $cohort->save();
            $response = [
                'error' =>  false,
                'code'  =>  200,
                'reason'    =>  'success'
            ];
        } catch (\Exception $exception) {
            $response = [
                'error' =>  true,
                'code'  =>  $exception->getCode(),
                'reason'    =>  $exception->getMessage()
            ];
        }

        return response()->json($response);
    }

    public function saveUpdates(Cohort $cohort, string $name)
    {
        $cohort->setName($name);

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
