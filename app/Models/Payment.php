<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    // Define which attributes are mass assignable
    protected $guarded = [];

    protected $casts = [
        'task_ids' => 'array',
    ];
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id'); // 'author' is the foreign key in the 'projects' table
    }
    public function author()
    {
        return $this->belongsTo(User::class, 'author', 'id'); // 'author' is the foreign key in the 'source' table
    }
}
