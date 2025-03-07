<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeBase extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'attachments' => 'array',  // Cast attachments as an array (json)
        'tags' => 'array',
    ];
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id'); // 'author' is the foreign key in the 'projects' table
    }
    public function author()
    {
        return $this->belongsTo(User::class, 'author', 'id'); // 'author' is the foreign key in the 'projects' table
    }
}
