<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Admin extends Model
{

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
//        $this->attributes['username'] = strtolower($username);
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
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

    public function updateRecord(int $id, Request $request)
    {
        $admin = $this->find($id);
        $admin->username = $request->input('username', $admin->username);
        $admin->password = $request->input('password', $admin->password);

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
    }

    public function changePassword(int $id, string $old_password, string $new_password)
    {
        $admin = Admin::find($id);
        if (hash_equals($admin->password, bcrypt($old_password))){
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
