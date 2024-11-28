<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharedTaskList extends Model
{
    use HasFactory;

    // Allow mass assignment for the following fields
    protected $fillable = [
        'task_list_id',
        'user_id',
        'permission',
    ];

    /**
     * Define the relationship with the `TaskList` model.
     */
    public function taskList()
    {
        return $this->belongsTo(TaskList::class);
    }

    /**
     * Define the relationship with the `User` model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
