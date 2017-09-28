<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tasks';

    protected $fillable = ['name', 'status', 'project_id', 'deadline'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    } 
}
