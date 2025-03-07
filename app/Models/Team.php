<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory, SoftDeletes;

    // Define which attributes are mass assignable
    protected $guarded = [];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id'); // 'author' is the foreign key in the 'projects' table
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function author()
    {
        return $this->belongsTo(User::class, 'author', 'id'); // 'author' is the foreign key in the 'source' table
    }
    public function user()
    {
        return $this->belongsTo(User::class); // Assuming foreign key is 'assign_id'
    }
    public function target()
    {
        return $this->hasMany(TeamTarget::class);
    }
}
