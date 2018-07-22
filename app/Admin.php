<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Admin extends Model
{
    protected $hidden = ['password'];

    /**
     * Admin constructor.
     * @param null $username
     * @param null $password
     */
    public function __construct($username = null, $password = null)
    {
        $this->username = $username;
        $this->password = $password;
    }

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
//    public function setUsernameAttribute($username)
//    {
//        $this->attributes['username'] = strtolower($username);
//    }

    /**
     * Hash password string
     *
     * @param $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function getUsernameAttribute()
    {
        return $this->attributes['username'];
    }

    public function store()
    {
        try {
            $this->save();
            return $this;
        } catch (\Exception $exception) {
            return response()->json([
                'error' => true,
                'code' => $exception->getCode(),
                'reason' => $exception->getMessage()
            ]);
        }

    }

    public static function changePassword(int $id, string $old_password, string $new_password)
    {
        $admin = Admin::find($id);
        if (Hash::check($old_password, $admin->password)){
            $admin->password = $new_password;
            try {
                $admin->save();
                return $admin;
            } catch (\Exception $exception){
                return response()->json([
                    'error' => true,
                    'code' => $exception->getCode(),
                    'reason' => $exception->getMessage()
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'code' => 900,
                'reason' => 'password mismatch'
            ]);
        }
    }
}
