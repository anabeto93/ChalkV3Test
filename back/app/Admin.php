<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Admin extends Model
{
    protected $fillable = ['username', 'password'];
    protected $hidden = ['password'];

    /**
     * The Institutions that belong to the Admin.
     */
    public function institutions() {
        return $this->belongsToMany(Institution::class);
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
