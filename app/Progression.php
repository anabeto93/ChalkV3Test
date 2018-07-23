<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Progression extends Model
{
    /**
     * The Session the Progression belongs to.
     */
    public function session() {
        return $this->belongsTo(Session::class);
    }

    /**
     * The User the Progression belongs to.
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}
