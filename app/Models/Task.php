<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
    * Get the description record associated with the task.
    */
    public function description()
    {
        return $this->hasOne('App\Models\TaskDescription');
    }

    /**
     * Get the histories of the task.
     */
    public function comments()
    {
        return $this->hasMany('App\TaskHistory');
    }
}
