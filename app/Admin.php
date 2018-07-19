<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    private $username;
    private $password;


    public function __construct($username = null, $password = null)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    protected $hidden = ['password'];

    /**
     * The Institutions that belong to the Admin.
     */
    public function institutions() {
        return $this->belongsToMany(Institution::class);
    }

    /**
     * Change username to lower case
     *
     * @param $username
     */
    public function setUsernameAttribute($username)
    {
        $this->attributes['username'] = strtolower($username);
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function store()
    {
        return $this->save();
    }
}
