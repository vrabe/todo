<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * The tasks that belong to the tag.
     */
    public function tasks()
    {
        return $this->belongsToMany('App\Models\Task','task_tag');
    }
}
