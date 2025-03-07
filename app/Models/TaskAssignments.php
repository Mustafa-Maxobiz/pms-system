<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAssignments extends Model
{
    use HasFactory, SoftDeletes;

     // Define the fields that are mass assignable
     protected $guarded = [];

    // Define relationships if necessary
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }
}
