<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    /**
     * The Institutions that belong to the Admin.
     */
    public function institutions() {
        return $this->belongsToMany(Institution::class);
    }

    public function setUserNameAttribute($username)
    {
        $this->attributes['username'] = strtolower($username);
    }
}
