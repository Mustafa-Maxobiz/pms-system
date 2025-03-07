<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubDepartment extends Model
{
    use HasFactory, SoftDeletes;

    // Define which attributes are mass assignable
    protected $guarded = [];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author', 'id');
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
