<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /**
     * Set Name Attribute of Course
     *
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->attributes['name'] = ucwords(strtolower($name));
    }

    /**
     * Set Description Attribute of Course
     *
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->attributes['description'] = $description;
    }

    /**
     * Set Teacher Attribute of Course
     *
     * @param string $teacher
     */
    public function setTeacher(string $teacher): void
    {
        $this->attributes['teacher'] = $teacher;
    }

    /**
     * Set Enabled Attribute of Course
     *
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->attributes['enabled'] = $enabled;
    }

    /**
     * Associate Course With Institution
     *
     * @param int $institution_id
     */
    public function setInstitutionId(int $institution_id): void
    {
        $this->institution()->associate($institution_id);
    }

    /**
     * The Institution the Course belongs to.
     */
    public function institution() {
        return $this->belongsTo(Institution::class);
    }

    /**
     * @return Institution
     */
    public function getInstitution(): Institution
    {
        return $this->institution()->first();
    }

    /**
     * The Folders that belong to the Course.
     */
    public function folders() {
        return $this->hasMany(Folder::class);
    }

    /**
     * @return array
     */
    public function getFolders(): array
    {
        return $this->folders()->get();
    }

    /**
     * The Sessions that belong to the Course.
     */
    public function sessions() {
        return $this->hasMany(Session::class);
    }

    /**
     * @return array
     */
    public function getSessions(): array
    {
        return $this->sessions()->get();
    }

    /**
     * The Users that belong to the Course.
     */
    public function users() {
        return $this->belongsToMany(User::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUsers(): Collection
    {
        return $this->users()->get();
    }

    /**
     * The Cohorts that belong to the Course.
     */
    public function cohorts() {
        return $this->belongsToMany(Cohort::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCohorts(): Collection
    {
        return $this->cohorts()->get();
    }

    /**
     * The Quizzes that belongs to the Course.
     */
    public function quizzes() {
        return $this->morphMany(Quiz::class, 'quizzable');
    }

    /**
     * @return Collection
     */
    public function getQuizzes(): Collection
    {
        return $this->quizzes()->get();
    }

    public function store(): array
    {
        try {
            $this->save();
            return [
                "error" =>  false,
                "code"  =>  200,
                "reason"    =>  'Course created!'
            ];
        } catch (\Exception $exception) {
            return [
                "error" =>  true,
                "code"  =>  500,
                "reason"    =>  $exception->getMessage()
            ];
        }
    }
}
