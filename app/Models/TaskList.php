<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function sharedWith()
    {
        return $this->belongsToMany(User::class, 'shared_task_lists')
                    ->withPivot('permission')
                    ->withTimestamps();
    }
}
