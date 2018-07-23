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

    public static function addInstitutions(int $admin_id, int $institution_id)
    {
        $admin = Admin::find($admin_id);
        $admin_institutions = $admin->institutions()->pluck('institutions.id')->toArray();
        if (in_array($institution_id, $admin_institutions)){
            $response = [
                'error' => true,
                'code' => 4005,
                'reason' => 'Institution already added'
            ];
        } elseif (is_null($admin->institutions()->attach($institution_id))) {
            $response = [
                'error' => false,
                'code' => 2000,
                'reason' => 'Institution added'
            ];
        } else {
            $response = [
                'error' => true,
                'code' => 5005,
                'reason' => 'Something went wrong, we could not complete your request at this time'
            ];
        }

        return response()->json($response);

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
