<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    /**
     * The Course the Session belongs to.
     */
    public function course() {
        return $this->belongsTo(Course::class);
    }

    /**
     * The Folder (nullable) the Session belongs to.
     */
    public function folder() {
        return $this->belongsTo(Folder::class);
    }

    /**
     * The Progressions that belong to the Session.
     */
    public function progressions() {
        return $this->hasMany(Progression::class);
    }

    /**
     * The Quiz that belongs to the Session.
     */
    public function quiz() {
        return $this->morphOne(Quiz::class, 'quizzable');
    }

    /**
     * The Files that belongs to the Session.
     */
    public function files() {
        return $this->morphMany(File::class, 'fileable');
    }

    /**
     * Has the user progressed past this session?
     */
    public function hasProgressed() {
        $user_id = Auth::user()->id;

        return filled($this->progressions()->where('user_id', $user_id)->get());
    }

    /**
     * Enabled Sessions.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnabled($query) {
        return $query->where('enabled', true);
    }
}
