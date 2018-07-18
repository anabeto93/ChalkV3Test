<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /**
     * The Institution the File belongs to.
     */
    public function institution() {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Get all of the owning fileable models.
     */
    public function fileable()
    {
        return $this->morphTo();
    }
}
