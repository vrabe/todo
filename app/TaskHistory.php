<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskHistory extends Model
{
    /**
    * Get the task belongs to the history record.
    */
    public function task()
    {
        return $this->belongsTo('App\Task');
    }
}
