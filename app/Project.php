<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'projects';

    // protected $fillable = ['name', 'user_id'];    
    protected $guarded = [];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
