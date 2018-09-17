<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['project_id', 'time_needed', 'priority', 'status', 'summary', 'start_time', 'due_time'];
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
    public function histories()
    {
        return $this->hasMany('App\Models\TaskHistory');
    }

    /**
     * Get the relationships from some tasks to this task.
     */
    public function fromTasks()
    {
        return $this->belongsToMany('App\Models\Task','task_relationships','destination_task_id','source_task_id')
                      ->withPivot('relationship_type');
    }

    /**
     * Get the relationships from this task to other tasks.
     */
    public function toTasks()
    {
        return $this->belongsToMany('App\Models\Task','task_relationships','source_task_id','destination_task_id')
                      ->withPivot('relationship_type');
    }

    /**
     * The tags that belong to the task.
     */
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag','task_tag');
    }

    /**
     * Get the project that owns the task.
     */
    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }
}
