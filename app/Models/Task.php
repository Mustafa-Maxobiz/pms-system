<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $guarded = [];

    // A task belongs to a project
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    
    public function taskType()
    {
        return $this->belongsTo(TaskType::class, 'task_type');
    }

    public function taskStatus()
    {
        return $this->hasMany(TaskStatusLogs::class, 'task_status_id');
    }

    public function taskStage()
    {
        return $this->belongsTo(TaskStage::class, 'task_stage');
    }

    public function taskPriority()
    {
        return $this->belongsTo(TaskPriority::class, 'task_priority');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
    public function assign()
    {
        return $this->belongsTo(User::class, 'assign_id');
    }
    public function finalize()
    {
        return $this->belongsTo(User::class, 'finalized');
    }
    public function finalized()
    {
        return $this->belongsTo(User::class, 'finalized');
    }

    public function csr()
    {
        return $this->belongsTo(User::class, 'author');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author');
    }

    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }

    public function taskStatusLogs()
    {
        return $this->hasMany(TaskStatusLogs::class)->limit(1)->orderBy('id', 'desc');
    }

    public function taskAssignments()
    {
        return $this->hasMany(TaskAssignments::class);
    }

    public function loadSubtasks()
    {
        return $this->hasMany(Subtask::class, 'task_id', 'id');
    }
    public function timeLogs()
    {
        return $this->hasMany(TimeLog::class, 'task_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'assign_id');
    }

}
