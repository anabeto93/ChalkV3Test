<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Vinkla\Hashids\Facades\Hashids;

class User extends Authenticatable
{
    public function setToken()
    {
        $this->attributes['token'] = Hashids::connection('user')->encode($this->attributes['id']);
        return $this;
    }
    public function setPhoneNumber(string $phone_number)
    {
        $this->attributes['phone_number'] = $phone_number;
        return $this;
    }

    public function setFirstName(string $first_name)
    {
        $this->attributes['first_name'] = ucwords(strtolower($first_name));
        return $this;
    }

    public function setLastName(string $last_name)
    {
        $this->attributes['last_name'] = ucwords(strtolower($last_name));
        return $this;
    }

    public function setInstitutionId(int $institution_id)
    {
        $this->institution()->associate($institution_id);
        return $this;
    }

    public function setCountry(string $country)
    {
        $this->attributes['country'] = $country;
        return $this;
    }

    public function setTokenLastUsed(string $datetime)
    {
        $this->attributes['token_last_used'] = $datetime;
        return $this;
    }
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

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCourses()
    {
        return $this->courses()->get();
    }

    public function store()
    {
        try {
            $this->save();
            $this->setToken()->save();
            session()->flash('success', 'User created!');
            return [
                'error' => false,
                'code' => 201,
                'reason' => 'User created!'
            ];
        } catch (\Exception $exception) {
            logger($exception);
            session()->flash('error', 'User could not be created!');
            return [
                'error' => true,
                'code' => 500,
                'reason' => 'User could not be created'
            ];
        }
    }

    public function login()
    {
        try {
            auth()->login($this);
            $this->setTokenLastUser()->save();
            return redirect()->to('home');
        } catch (\Exception $exception) {
            session()->flash('error', 'Something went wrong, please try again later!');
            return redirect()->back();
        }
    }

    public function getNameAttribute() {
        return $this->first_name . " " . $this->last_name;
    }
}
