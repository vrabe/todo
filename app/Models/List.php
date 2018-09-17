<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class List extends Model
{
    /**
     * Get the tasks belong to the list.
     */
    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }
}
