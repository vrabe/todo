<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * Get the tasks belong to the project.
     */
    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }
}
