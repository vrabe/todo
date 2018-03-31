<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
    * Get the description record associated with the task.
    */
    public function description()
    {
        return $this->hasOne('App\TaskDescription');
    }
}
